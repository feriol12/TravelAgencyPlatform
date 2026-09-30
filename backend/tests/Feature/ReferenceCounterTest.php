<?php

namespace Tests\Feature;

use App\Enums\ReferenceType;
use App\Models\ReferenceCounter as ReferenceCounterModel;
use App\Services\ReferenceCounter;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class ReferenceCounterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ReferenceCounterModel::query()->delete();
    }

    /**
     * Construit l'environnement des workers à partir de la configuration de
     * connexion réellement résolue par le processus PHPUnit parent.
     *
     * Aucun nom de base ne doit être codé en dur : phpunit.xml, une variable
     * d'environnement ou un .env local peuvent définir la base de test effective.
     *
     * @return array<string, string>
     */
    private function workerEnvironment(): array
    {
        $connection = DB::connection();
        $config = $connection->getConfig();

        return [
            'APP_ENV' => 'testing',
            'DB_CONNECTION' => (string) ($config['driver'] ?? ''),
            'DB_HOST' => (string) ($config['host'] ?? ''),
            'DB_PORT' => (string) ($config['port'] ?? ''),
            'DB_SOCKET' => (string) ($config['unix_socket'] ?? ''),
            'DB_DATABASE' => (string) $connection->getDatabaseName(),
            'DB_USERNAME' => (string) ($config['username'] ?? ''),
            'DB_PASSWORD' => (string) ($config['password'] ?? ''),
            'DB_URL' => (string) ($config['url'] ?? ''),
        ];
    }

    /**
     * A. Premier numéro : REQ -> REQ-YYYY-000001
     */
    public function test_first_reference_starts_at_000001(): void
    {
        $ref = ReferenceCounter::next('REQ', 2026);

        $this->assertSame('REQ-2026-000001', $ref);

        $counter = ReferenceCounterModel::where('prefix', 'REQ')
            ->where('year', 2026)
            ->first();

        $this->assertNotNull($counter);
        $this->assertSame(1, $counter->current_value);
    }

    /**
     * B. Incrément séquentiel : 000001, 000002, 000003
     */
    public function test_sequential_increments(): void
    {
        $ref1 = ReferenceCounter::next('REQ', 2026);
        $ref2 = ReferenceCounter::next('REQ', 2026);
        $ref3 = ReferenceCounter::next('REQ', 2026);

        $this->assertSame('REQ-2026-000001', $ref1);
        $this->assertSame('REQ-2026-000002', $ref2);
        $this->assertSame('REQ-2026-000003', $ref3);

        $counter = ReferenceCounterModel::where('prefix', 'REQ')
            ->where('year', 2026)
            ->first();

        $this->assertSame(3, $counter->current_value);
    }

    /**
     * C. Séparation des types : REQ, PRJ, REC ont chacun leur séquence.
     */
    public function test_types_have_independent_sequences(): void
    {
        $req1 = ReferenceCounter::next('REQ', 2026);
        $prj1 = ReferenceCounter::next('PRJ', 2026);
        $rec1 = ReferenceCounter::next('REC', 2026);

        $this->assertSame('REQ-2026-000001', $req1);
        $this->assertSame('PRJ-2026-000001', $prj1);
        $this->assertSame('REC-2026-000001', $rec1);

        $req2 = ReferenceCounter::next(ReferenceType::REQ, 2026);
        $prj2 = ReferenceCounter::next(ReferenceType::PRJ, 2026);
        $rec2 = ReferenceCounter::next(ReferenceType::REC, 2026);

        $this->assertSame('REQ-2026-000002', $req2);
        $this->assertSame('PRJ-2026-000002', $prj2);
        $this->assertSame('REC-2026-000002', $rec2);
    }

    /**
     * D. Changement d'année : 2026 -> 000001, 000002 puis 2027 -> 000001
     */
    public function test_yearly_sequence_reset_with_explicit_year(): void
    {
        $ref2026A = ReferenceCounter::next('REQ', 2026);
        $ref2026B = ReferenceCounter::next('REQ', 2026);

        $this->assertSame('REQ-2026-000001', $ref2026A);
        $this->assertSame('REQ-2026-000002', $ref2026B);

        $ref2027A = ReferenceCounter::next('REQ', 2027);

        $this->assertSame('REQ-2027-000001', $ref2027A);

        $counter2026 = ReferenceCounterModel::where('prefix', 'REQ')->where('year', 2026)->first();
        $counter2027 = ReferenceCounterModel::where('prefix', 'REQ')->where('year', 2027)->first();

        $this->assertSame(2, $counter2026->current_value);
        $this->assertSame(1, $counter2027->current_value);
    }

    /**
     * D (suite). Changement d'année basé sur la date courante (Carbon::setTestNow).
     */
    public function test_yearly_sequence_reset_with_carbon_time(): void
    {
        Carbon::setTestNow('2026-06-15 10:00:00');
        $ref2026 = ReferenceCounter::next('REQ');
        $this->assertSame('REQ-2026-000001', $ref2026);

        Carbon::setTestNow('2027-01-01 00:00:00');
        $ref2027 = ReferenceCounter::next('REQ');
        $this->assertSame('REQ-2027-000001', $ref2027);

        Carbon::setTestNow(); // reset
    }

    /**
     * E. Format : PREFIX-YYYY-NNNNNN avec exactement 6 chiffres.
     */
    public function test_reference_format_strictly_matches_specification(): void
    {
        $req = ReferenceCounter::next('REQ', 2026);
        $prj = ReferenceCounter::next('PRJ', 2026);
        $rec = ReferenceCounter::next('REC', 2026);

        $pattern = '/^(REQ|PRJ|REC)-\d{4}-\d{6}$/';

        $this->assertMatchesRegularExpression($pattern, $req);
        $this->assertMatchesRegularExpression($pattern, $prj);
        $this->assertMatchesRegularExpression($pattern, $rec);

        $this->assertSame('REQ-2026-000001', $req);
        $this->assertSame('PRJ-2026-000001', $prj);
        $this->assertSame('REC-2026-000001', $rec);
    }

    /**
     * F. Unicité : aucune référence générée deux fois.
     */
    public function test_uniqueness_across_multiple_generations(): void
    {
        $references = [];
        for ($i = 0; $i < 50; $i++) {
            $references[] = ReferenceCounter::next('REQ', 2026);
        }

        $this->assertCount(50, $references);
        $this->assertCount(50, array_unique($references));
        $this->assertSame('REQ-2026-000050', end($references));
    }

    /**
     * G. Concurrence : plusieurs processus simultanés.
     * N références, N références uniques, aucune collision, numéros exactement 1..N, aucune valeur manquante.
     */
    public function test_concurrent_generation_produces_no_collisions_and_continuous_sequence(): void
    {
        $processesCount = 5;
        $callsPerProcess = 4;
        $expectedTotal = $processesCount * $callsPerProcess;
        $testYear = 2050;

        $workerScript = __DIR__.'/concurrency_worker.php';
        $parentDatabase = DB::connection()->getDatabaseName();
        $env = $this->workerEnvironment();

        /** @var Process[] $processes */
        $processes = [];

        for ($i = 0; $i < $processesCount; $i++) {
            $process = new Process(
                [PHP_BINARY, $workerScript, 'REQ', (string) $testYear, (string) $callsPerProcess],
                base_path(),
                $env
            );
            $process->setTimeout(60);
            $process->start();
            $processes[] = $process;
        }

        $allReferences = [];

        foreach ($processes as $process) {
            $process->wait();
        }

        foreach ($processes as $process) {
            $this->assertTrue($process->isSuccessful(), 'Child process failed: '.$process->getErrorOutput());

            $output = trim($process->getOutput());
            $lines = array_filter(explode("\n", str_replace("\r", '', $output)));
            $lastLine = end($lines);
            try {
                $payload = json_decode((string) $lastLine, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $exception) {
                $this->fail('Child process returned invalid JSON: '.$process->getOutput());
            }
            $this->assertIsArray($payload);

            // 0. Le worker doit utiliser exactement la même base que le processus PHPUnit parent.
            $this->assertSame(
                $parentDatabase,
                $payload['database'] ?? null,
                'Worker did not use the database configured for the PHPUnit parent process.'
            );

            $refs = $payload['references'] ?? null;
            $this->assertIsArray($refs);
            $this->assertCount($callsPerProcess, $refs);

            $allReferences = array_merge($allReferences, $refs);
        }

        // 1. Exactement N références générées
        $this->assertCount($expectedTotal, $allReferences);

        // 2. Aucune référence dupliquée / collision
        $this->assertCount($expectedTotal, array_unique($allReferences));

        // 3. Les numéros générés couvrent exactement 1..N sans manque ni doublon
        $numbers = array_map(function ($ref) use ($testYear) {
            $matched = preg_match('/^REQ-'.$testYear.'-(\d{6})$/', $ref, $matches);
            $this->assertSame(1, $matched, "Invalid reference format: {$ref}");

            return (int) $matches[1];
        }, $allReferences);

        sort($numbers);
        $this->assertSame(range(1, $expectedTotal), $numbers);

        // 4. Intégrité de la table : compteur final exactement égal à N
        $counter = ReferenceCounterModel::where('prefix', 'REQ')->where('year', $testYear)->first();
        $this->assertNotNull($counter);
        $this->assertSame($expectedTotal, $counter->current_value);
    }

    /**
     * H. Intégrité DB : la contrainte unique (prefix, year) empêche les doublons.
     */
    public function test_database_unique_constraint_prevents_duplicate_counters(): void
    {
        ReferenceCounterModel::create([
            'prefix' => 'REQ',
            'year' => 2026,
            'current_value' => 5,
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        ReferenceCounterModel::create([
            'prefix' => 'REQ',
            'year' => 2026,
            'current_value' => 10,
        ]);
    }

    /**
     * I. Types invalides : INVALID, req, FOO, '' doivent être rejetés proprement.
     */
    public function test_invalid_types_are_rejected(): void
    {
        $invalidTypes = ['INVALID', 'req', 'FOO', '', '123', 'REQ_EXTRA'];

        foreach ($invalidTypes as $invalidType) {
            try {
                ReferenceCounter::next($invalidType, 2026);
                $this->fail("Expected InvalidArgumentException for invalid type '{$invalidType}' was not thrown.");
            } catch (InvalidArgumentException $e) {
                $this->assertStringContainsString('Invalid reference type', $e->getMessage());
                $this->assertStringContainsString('Allowed types are: REQ, PRJ, REC', $e->getMessage());
            }
        }
    }

    /**
     * J. Années hors plage : rejetées proprement, aucune référence à cinq chiffres.
     */
    public function test_out_of_range_years_are_rejected(): void
    {
        $invalidYears = [-2026, -1, 0, 1, 999, 10000, 20000, PHP_INT_MAX];

        foreach ($invalidYears as $invalidYear) {
            try {
                ReferenceCounter::next('REQ', $invalidYear);
                $this->fail("Expected InvalidArgumentException for invalid year '{$invalidYear}' was not thrown.");
            } catch (InvalidArgumentException $e) {
                $this->assertStringContainsString('Invalid reference year', $e->getMessage());
                $this->assertStringContainsString('1000 and 9999', $e->getMessage());
            }
        }

        // Une année invalide ne doit écrire aucun compteur.
        $this->assertSame(0, ReferenceCounterModel::query()->count());
    }

    /**
     * K. Bornes admises : 1000 et 9999 restent des années à quatre chiffres.
     */
    public function test_boundary_years_produce_four_digit_references(): void
    {
        $lowest = ReferenceCounter::next('REQ', 1000);
        $highest = ReferenceCounter::next(ReferenceType::PRJ, 9999);

        $this->assertSame('REQ-1000-000001', $lowest);
        $this->assertSame('PRJ-9999-000001', $highest);

        $pattern = '/^(REQ|PRJ|REC)-\d{4}-\d{6}$/';

        $this->assertMatchesRegularExpression($pattern, $lowest);
        $this->assertMatchesRegularExpression($pattern, $highest);
    }
}

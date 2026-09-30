<?php

use App\Services\ReferenceCounter;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$type = $argv[1] ?? 'REQ';
$year = isset($argv[2]) ? (int) $argv[2] : 2026;
$count = isset($argv[3]) ? (int) $argv[3] : 1;

$refs = [];
for ($i = 0; $i < $count; $i++) {
    $refs[] = ReferenceCounter::next($type, $year);
}

// La base réellement utilisée est renvoyée au test parent pour qu'il puisse
// vérifier que tous les workers partagent la même base isolée que PHPUnit.
echo PHP_EOL;
echo json_encode([
    'database' => DB::connection()->getDatabaseName(),
    'references' => $refs,
], JSON_THROW_ON_ERROR);

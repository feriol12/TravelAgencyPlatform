<?php

namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;

class CorsConfigTest extends TestCase
{
    private const ENV_KEY = 'CORS_ALLOWED_ORIGINS';

    protected function tearDown(): void
    {
        putenv(self::ENV_KEY);
        unset($_ENV[self::ENV_KEY], $_SERVER[self::ENV_KEY]);

        parent::tearDown();
    }

    public function test_allowed_origins_are_trimmed_and_empty_entries_are_removed(): void
    {
        $this->setEnv('http://localhost:5173, http://127.0.0.1:5173 , ,http://foo.test ');

        $config = require __DIR__.'/../../../config/cors.php';

        $this->assertSame([
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'http://foo.test',
        ], $config['allowed_origins']);
    }

    public function test_a_single_configured_origin_is_still_trimmed(): void
    {
        $this->setEnv('  http://localhost:5173  ');

        $config = require __DIR__.'/../../../config/cors.php';

        $this->assertSame(['http://localhost:5173'], $config['allowed_origins']);
    }

    private function setEnv(string $value): void
    {
        putenv(self::ENV_KEY.'='.$value);
        $_ENV[self::ENV_KEY] = $value;
        $_SERVER[self::ENV_KEY] = $value;
    }
}

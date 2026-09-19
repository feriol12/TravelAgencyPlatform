<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        // A cached development configuration bypasses PHPUnit's environment.
        if (is_file(dirname(__DIR__).'/bootstrap/cache/config.php')) {
            throw new RuntimeException('Clear the configuration cache before running tests.');
        }

        $app = parent::createApplication();
        $connection = $app['db']->connection();

        // Runs before test traits can execute migrations or refresh the database.
        if (! $app->environment('testing')
            || $app->configurationIsCached()
            || $connection->getDriverName() !== 'mysql'
            || $connection->getConfig('unix_socket') !== ''
            || preg_match('/\Atravel_agency_test(?:_[A-Za-z0-9_]+)?\z/', (string) $connection->getDatabaseName()) !== 1) {
            throw new RuntimeException('Tests require a MySQL database named travel_agency_test or travel_agency_test_<suffix>.');
        }

        return $app;
    }
}

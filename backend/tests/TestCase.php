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
            || $connection->getConfig('host') !== '127.0.0.1'
            || (string) $connection->getConfig('port') !== '3306'
            || $connection->getConfig('unix_socket') !== ''
            || $connection->getDatabaseName() !== 'travel_agency_test') {
            throw new RuntimeException('Tests require the local MySQL travel_agency_test database.');
        }

        return $app;
    }
}

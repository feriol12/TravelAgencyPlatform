<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseIsolationTest extends TestCase
{
    public function test_mysql_connection_uses_the_isolated_test_database(): void
    {
        $this->assertTrue($this->app->environment('testing'));
        $this->assertSame('mysql', DB::connection()->getDriverName());
        $database = DB::selectOne('SELECT DATABASE() AS name')->name;

        $this->assertSame(DB::connection()->getDatabaseName(), $database);
        $this->assertMatchesRegularExpression('/\Atravel_agency_test(?:_[A-Za-z0-9_]+)?\z/', $database);
        $this->assertNotSame('travel_agency_dev', $database);
    }
}

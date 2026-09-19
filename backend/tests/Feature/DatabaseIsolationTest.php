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
        $this->assertSame('travel_agency_test', DB::selectOne('SELECT DATABASE() AS name')->name);
    }
}

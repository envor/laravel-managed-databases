<?php

namespace Envor\ManagedDatabases\Tests;

use Envor\ManagedDatabases\ManagedDatabasesServiceProvider;
use Envor\SchemaMacros\SchemaMacrosServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SchemaMacrosServiceProvider::class,
            ManagedDatabasesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite_tests');

        config()->set('database.connections.sqlite_tests', [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ]);
    }
}

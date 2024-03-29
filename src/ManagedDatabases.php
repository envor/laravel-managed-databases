<?php

namespace Envor\ManagedDatabases;

use Envor\ManagedDatabases\Actions\MakesDatabaseName;
use Envor\SchemaMacros\SchemaMacros;
use Envor\SchemaMacros\UnsupportedDriver;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Stringable;

class ManagedDatabases
{
    public static function configureTestConnections()
    {
        config(['database.connections.testing_platform' => array_merge(config('database.connections.sqlite'), ['database' => ':memory:']),
        ]);

        config(['database.connections.testing_app' => array_merge(config('database.connections.sqlite'), ['database' => ':memory:']),
        ]);
    }

    public static function configureManagerConnections()
    {
        config(['database.connections.manager_sqlite' => array_merge(
            config('database.connections.manager_sqlite', config('database.connections.sqlite')), [
                'database' => ':memory:',
            ])]);

        config(['database.connections.manager_mysql' => config('database.connections.manager_mysql', config('database.connections.mysql')),
        ]);

        config(['database.connections.manager_mariadb' => config('database.connections.manager_mariadb', config('database.connections.mariadb')),
        ]);
    }

    public static function cacheCurrentConnection()
    {
        $currentConnection = config('database.default');
        $currentConnectionConfig = config("database.connections.{$currentConnection}");

        config(['database.connections.current' => $currentConnectionConfig]);
    }

    public static function setDefaultConnection($connection)
    {
        config(['database.default' => $connection]);

        DB::purge($connection);

        DB::connection($connection)->reconnect();
    }

    public static function makeDatabaseNamesUsing($callback)
    {
        return app()->bind(MakesDatabaseName::class, $callback);
    }

    public static function makeDatabaseName($database, $managerConnection = 'manager_sqlite', $disk = 'local'): string
    {
        return app(MakesDatabaseName::class)($database, $managerConnection, $disk);
    }

    public static function createsConnectionConfig(string|Stringable $database, $managerConnection = 'manager_sqlite', $disk = 'local'): string
    {

        config(['database.connections.'.$database => array_merge(
            config('database.connections.'.$managerConnection), [
                'database' => static::makeDatabaseName($database, $managerConnection, $disk),
            ])]);

        return $database;
    }

    public static function createsInMemoryConnectionConfig(string|Stringable $database): string
    {

        config(['database.connections.'.$database => array_merge(
            config('database.connections.manager_sqlite'), [
                'database' => ':memory:',
            ])]);

        return $database;
    }

    public static function restoreCurrentConnection()
    {
        DB::purge('current');

        $currentConnection = config('database.default');
        $currentConnectionConfig = config('database.connections.current');

        config(['database.connections.'.$currentConnection => $currentConnectionConfig]);

        DB::purge($currentConnection);

        static::dropConnection('current');
    }

    public static function dropConnection($database)
    {
        $database = (string) $database;

        DB::purge($database);

        config(['database.connections.'.$database => null]);
    }

    public static function createDatabase($database, $managerConnection = 'manager_sqlite', $disk = 'local')
    {
        static::cacheCurrentConnection();

        static::setDefaultConnection($managerConnection);

        if (! in_array($driver = config('database.connections.'.$managerConnection.'.driver'), SchemaMacros::supportedDrivers($macro = 'createDatabaseIfNotExists'))) {
            throw new UnsupportedDriver('The driver '.$driver.' is not supported by the '.$macro.' macro');
        }

        if (Schema::databaseExists(static::makeDatabaseName($database, $managerConnection, $disk))) {
            $database = (string) $database.'_1';
            static::createDatabase($database, $managerConnection, $disk);
        }

        $successfullyCreated = Schema::createDatabaseIfNotExists(static::makeDatabaseName($database, $managerConnection, $disk));

        static::restoreCurrentConnection();

        return $database;
    }

    public static function migrateDatabase($database)
    {
        static::runOnDatabase($database, function () {
            Artisan::call('migrate', ['--force' => true]);
        });
    }

    public static function runOnDatabase($database, $callback, $managerConnection = 'manager_sqlite')
    {
        $database = (string) $database;

        static::cacheCurrentConnection();

        static::configureDatabase($database, $managerConnection);

        $result = $callback();

        static::restoreCurrentConnection();

        static::dropConnection($database);

        return $result;
    }

    public static function configureDatabase($database, $managerConnection = 'manager_sqlite', $disk = 'local')
    {
        $database = static::createsConnectionConfig($database, $managerConnection, $disk);

        static::setDefaultConnection($database);
    }

    public static function configureInMemoryDatabase($database)
    {
        $database = static::createsInMemoryConnectionConfig($database);

        static::setDefaultConnection($database);
    }

    public static function createInMemoryDatabase($database)
    {
        $database = static::createsInMemoryConnectionConfig($database);
    }
}

<?php

namespace Envor\ManagedDatabases;

class ManagedDatabases
{
    public static function configureManagedConnections()
    {
        config(['database.connections.managed_sqlite' => array_merge(config('database.connections.managed_sqlite', 'database.connections.sqlite'), [
            'database' => null,
        ])]);

        config(['database.connections.managed_mysql' => array_merge(config('database.connections.managed_mysql', 'database.connections.mysql'), [
            'database' => null,
        ])]);

        config(['database.connections.managed_mariadb' => array_merge(config('database.connections.managed_mysql', 'database.connections.mariadb'), [
            'database' => null,
        ])]);
    }

    public static function configureSystemConnections()
    {
        config(['database.connections.system_sqlite' => config('database.connections.system_sqlite', 'database.connections.sqlite')]);

        config(['database.connections.system_sqlite.database' => ':memory:']);

        config(['database.connections.system_mysql' => config('database.connections.mysql', 'database.connections.mysql')]);

        config(['database.connections.system_mariadb' => config('database.connections.system_mariadb', 'database.connections.mariadb')]);
    }
}

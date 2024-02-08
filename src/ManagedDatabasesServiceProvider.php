<?php

namespace Envor\ManagedDatabases;

use Envor\ManagedDatabases\Commands\ManagedDatabasesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ManagedDatabasesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-managed-databases')
            // ->hasConfigFile()
            // ->hasViews()
            // ->hasMigration('create_laravel-managed-databases_table')
            // ->runsMigrations(/** todo: ignore option */)
            ->hasCommand(ManagedDatabasesCommand::class);
    }

    public function packageRegistered()
    {
        ManagedDatabases::configureManagerConnections();
    }
}

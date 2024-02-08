<?php

namespace Envor\ManagedDatabases\Commands;

use Envor\ManagedDatabases\ManagedDatabases;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ManagedDatabasesCommand extends Command
{
    public $signature = 'managed-databases:run {artisanCommand} {--database= : The managed database to run the command on} {--managerConnection=manager_sqlite}';

    public $description = 'Run an artisan command on a managed database';

    public function handle(): int
    {
        $artisanCommand = function () {
            return Artisan::call($this->argument('artisanCommand'), [], $this->output);
        };

        return ManagedDatabases::runOnDatabase(
            $this->option('database'),
            $artisanCommand,
            $this->option('managerConnection'),
        );
    }
}

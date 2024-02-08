<?php

namespace Envor\ManagedDatabases\Commands;

use Envor\ManagedDatabases\ManagedDatabases;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ManagedDatabasesCommand extends Command
{
    public $signature = 'managed-databases:run {artisanCommand?} {--database= : The managed database to run the command on} {--managerConnection=manager_sqlite}';

    public $description = 'Run an artisan command on a managed database';

    public function handle(): int
    {

        if (! $artisanCommand = $this->argument('artisanCommand')) {
            $artisanCommand = $this->ask('Which artisan command do you want to run for '.$this->option('database').'?');
        }

        $artisanCommandCallback = function () use ($artisanCommand) {
            return Artisan::call($artisanCommand, [], $this->output);
        };

        return ManagedDatabases::runOnDatabase(
            $this->option('database'),
            $artisanCommandCallback,
            $this->option('managerConnection'),
        );
    }
}

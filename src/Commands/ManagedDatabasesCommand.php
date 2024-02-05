<?php

namespace Envor\ManagedDatabases\Commands;

use Illuminate\Console\Command;

class ManagedDatabasesCommand extends Command
{
    public $signature = 'laravel-managed-databases';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

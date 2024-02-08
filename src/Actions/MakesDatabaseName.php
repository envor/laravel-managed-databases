<?php

namespace Envor\ManagedDatabases\Actions;

use Illuminate\Support\Facades\Storage;
use Stringable;

class MakesDatabaseName
{
    public function __invoke(string|Stringable $database, string|Stringable $managerConnection = 'manager_sqlite', $disk = 'local'): string
    {
        $databaseName = str()->of($database)->start('managed_');

        if (config('database.connections.'.$managerConnection.'.driver') === 'sqlite') {
            $databaseName = Storage::disk($disk)->path((string) $databaseName->finish('.sqlite'));
        }

        return (string) $databaseName;
    }
}

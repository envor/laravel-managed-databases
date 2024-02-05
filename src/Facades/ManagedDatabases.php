<?php

namespace Envor\ManagedDatabases\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Envor\ManagedDatabases\ManagedDatabases
 */
class ManagedDatabases extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Envor\ManagedDatabases\ManagedDatabases::class;
    }
}

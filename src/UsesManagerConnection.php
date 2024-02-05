<?php

namespace Envor\ManagedDatabases;

trait UsesManagerConnection
{
    public function getConnectionName()
    {
        return config('managed-databases.manager_connection');
    }
}
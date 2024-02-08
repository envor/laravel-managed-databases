<?php

namespace Envor\ManagedDatabases\Tests\Fixtures;

trait UsesManagerConnection
{
    public function getConnectionName()
    {
        return 'sqlite_tests';
    }
}

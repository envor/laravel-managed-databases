<?php

use Envor\ManagedDatabases\ManagedDatabases;

it('can configure an in memory database for the testing', function () {
    ManagedDatabases::configureInMemoryDatabase('testing_123');

    expect(config('database.connections.testing_123.database'))->toBe(':memory:');

    expect(config('database.default'))->toBe('testing_123');
});
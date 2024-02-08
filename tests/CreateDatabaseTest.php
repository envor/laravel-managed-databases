<?php

use Envor\ManagedDatabases\ManagedDatabases;
use Envor\ManagedDatabases\Tests\Fixtures\ManagedDatabase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');

    Schema::connection('sqlite_tests')->create('managed_databases', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('manager_connection')->default('manager_sqlite');
        $table->string('type')->default('sqlite');
        $table->timestamps();
    });
});

afterEach(function () {
    // Storage::fake('local');

    Schema::connection('sqlite_tests')->dropIfExists('managed_databases');
});

it('can create a database', function () {
    ManagedDatabase::create([
        'name' => 'md_test',
        'manager_connection' => 'manager_sqlite',
        'type' => 'sqlite',
    ]);

    expect(Storage::disk('local')->exists('managed_md_test.sqlite'))->toBeTrue();

    expect(ManagedDatabases::runOnDatabase('md_test', function () {
        return Schema::hasTable('migrations');
    }))->toBeTrue();

});

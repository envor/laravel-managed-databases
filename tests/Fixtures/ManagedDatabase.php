<?php

namespace Envor\ManagedDatabases\Tests\Fixtures;

use Envor\ManagedDatabases\ManagedDatabases;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class ManagedDatabase extends Model
{
    use UsesManagerConnection;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::created(function (ManagedDatabase $model) {

            if ($model->type === 'sqlite') {

                ManagedDatabases::createDatabase($model->name);

                ManagedDatabases::runOnDatabase($model->name, function () {
                    Artisan::call('migrate', ['--force' => true]);
                });

                return;
            }

            if ($model->type === 'mysql' || $model->type === 'mariadb') {

                ManagedDatabases::createDatabase($model->name, 'manager_'.$model->type);

                ManagedDatabases::runOnDatabase($model->name, function () {
                    Artisan::call('migrate', ['--force' => true]);
                });

                return;
            }

            throw new \Exception('Database type not supported');
        });
    }
}

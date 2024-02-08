## A small package for managing multiple databases and their connections at runtime using laravel tools

[![Latest Version on Packagist](https://img.shields.io/packagist/v/envor/laravel-managed-databases.svg?style=flat-square)](https://packagist.org/packages/envor/laravel-managed-databases)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/envor/laravel-managed-databases/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/envor/laravel-managed-databases/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/envor/laravel-managed-databases/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/envor/laravel-managed-databases/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/envor/laravel-managed-databases.svg?style=flat-square)](https://packagist.org/packages/envor/laravel-managed-databases)

## Installation

You can install the package via composer:

```bash
composer require envor/laravel-managed-databases
```

## Usage

[createDatabase()](#manageddatabasescreatedatabase)    
[runOnDatabase()](#manageddatabasesrunondatabase)    
[configureDatabase()](#manageddatabasesconfiguredatabase)

### #`ManagedDatabases::createDatabase()`

The `createDatabase()` method will

- Cache the current default database connection config
- Set the connection to the `$managerConnection`
- Purge the connection and reconnect
- Create the physical database
- Purge the connection
- Restore original default connection
- Purge and reconnect

> [!TIP]
> The `$managerConnection` must exist and be a configured database connection.    
> This package creates a few defaults: `manager_sqlite`, `manager_mysql` and `manager_mariadb`.    
> They are bootstrapped into memory by cloning the default configs for `sqlite`, `mysql` and `mariadb`.

```php
$managerConnection = 'manager_sqlite';
$name = 'database'

ManagedDatabases::createDatabase($name, $managerConnection);
```

### #`ManagedDatabases::runOnDatabase()`

The `runOnDatabase()` method will connect the given `$database` using a new connection created with the credentials and options from the given `$managerConnection`, execute the given `$callback`, then finally, restore the original default database connection.

- Cache the current default database connection config
- Create a new connection config for the database by cloning the `$managerConnection` config
- Set the database as default and connect to it
- Run the given callback
- Purge the connection
- Restore original default connection
- Purge and reconnect

```php
ManagedDatabases::runOnDatabase(
    $database = 'database', 
    $callback = fn() => Artisan::call('migrate', ['--force' => true]), 
    $managerConnection = 'manager_sqlite'
);
```

The package also includes an `artisan` wrapper for the `runOnDatabase()` method called `managed-databases:run`.
The simplest and most harmless way to check it out is by pasting the following command into your terminal:

```bash
php artisan managed-databases:run "migrate:fresh --seed" --database=":memory:" --managerConnection="sqlite"
```

This will run your migrations and seeders harmlessly against an in-memory sqlite database. A great way to quickly check if they can run without errors.

### #`ManagedDatabases::configureDatabase()`

The `configureDatabase()` method will set the given database as the default on on a brand new connection modeled after the given `$managerConnection`

```php
use Envor\ManagedDatabases\ManagedDatabases;

ManagedDatabases::createDatabase('database2', 'sqlite');

ManagedDatabases::useDatabase('database2', 'sqlite');

config('database.default');

// database2

config('database.connections.database2')

// [
//     "driver" => "sqlite",
//     "url" => null,
//     "database" => "/home/forge/mysite.com/storage/app/managed_database2.sqlite",
//     "prefix" => "",
//     "foreign_key_constraints" => true,
// ]
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [inmanturbo](https://github.com/envor)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

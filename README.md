# This is my package laravel-managed-databases

[![Latest Version on Packagist](https://img.shields.io/packagist/v/envor/laravel-managed-databases.svg?style=flat-square)](https://packagist.org/packages/envor/laravel-managed-databases)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/envor/laravel-managed-databases/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/envor/laravel-managed-databases/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/envor/laravel-managed-databases/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/envor/laravel-managed-databases/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/envor/laravel-managed-databases.svg?style=flat-square)](https://packagist.org/packages/envor/laravel-managed-databases)

## Installation

You can install the package via composer:

```bash
composer require envor/laravel-managed-databases
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="managed-databases-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="managed-databases-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$manager = User::first();

$managedDatabase = $manager->managedDatabases()->create([
    'name' => 'unique_name',
    'system_connection' => 'system_sqlite'
    'template_connection' => 'managed_sqlite',
    'type' => 'sqlite', 
]);
```

[configure()](#configure)

### #`configure()`

The `configure()` method configures the database as the default.

```php


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

# laravel-resources

> Note: The `0.x` branch and `~0.9` releases are for Laravel 4.  Use `master` or `>=1.0` for Laravel 5.


## Installation

Include the package:

```sh
composer require "cviebrock/laravel-resources:~0.9"
```

Add service provider and facades to `app/config.php`:

```php
'providers' => [
    'Cviebrock\LaravelResources\ServiceProvider',
],
'aliases' => [
    'Resource' => 'Cviebrock\LaravelResources\Facades\Resource',
	'ResourceGroup' => 'Cviebrock\LaravelResources\Facades\ResourceGroup',
]
```

Publish the configuration:

```sh
php artisan config:publish "cviebrock/laravel-resources"
```

Edit the configuration (if needed), then generate and run the migration:

```sh
php artisan resources:table
php artisan migrate
```

## Configuration

Update `app/config/packages/cviebrock/laravel-resources/resources.php` with the array of keys/descriptor classes you need.

Then, run the initial import to load those values in to the database:

```sh
php artisan resources:import
```

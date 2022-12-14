
# Laravel validation attributes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uzbek/laravel-validation-attributes.svg?style=flat-square)](https://packagist.org/packages/uzbek/laravel-validation-attributes)
[![Total Downloads](https://img.shields.io/packagist/dt/uzbek/laravel-validation-attributes.svg?style=flat-square)](https://packagist.org/packages/uzbek/laravel-validation-attributes)
[![Licence](https://img.shields.io/packagist/l/uzbek/laravel-validation-attributes?style=flat-square)](https://packagist.org/packages/uzbek/laravel-validation-attributes)

[//]: # ([![GitHub Tests Action Status]&#40;https://img.shields.io/github/workflow/status/professor93/laravel-validation-attributes/run-tests?label=tests&#41;]&#40;https://github.com/professor93/laravel-validation-attributes/actions?query=workflow%3Arun-tests+branch%3Amain&#41;)
[//]: # ([![GitHub Code Style Action Status]&#40;https://img.shields.io/github/workflow/status/professor93/laravel-validation-attributes/Check%20&%20fix%20styling?label=code%20style&#41;]&#40;https://github.com/professor93/laravel-validation-attributes/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain&#41;)


## Installation

You can install the package via composer:

```bash
composer require uzbek/laravel-validation-attributes
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-validation-attributes-config"
```

This is the contents of the published config file:

```php
return [
    'enabled' => true,
    'cache_name' => 'laravel-validation-attributes',
    'cache_time' => 10,
    'directories' => [
        app_path('Http/Controllers'),
    ],
];
```

## Usage
Add to kernel's middleware groups (web) this middleware:
```php
Uzbek\LaravelValidationAttributes\ValidationAttributesMiddleware::class
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/professor93/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Inoyatulloh](https://github.com/professor93)

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

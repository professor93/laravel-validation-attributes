<?php

namespace Uzbek\LaravelValidationAttributes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelValidationAttributesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         */
        $package->name('laravel-validation-attributes')->hasConfigFile();
        $this->app->singleton(LaravelValidationAttributes::class, fn () => new LaravelValidationAttributes);

        $router = $this->app['router'];
        $router->aliasMiddleware('valid-attr', ValidationAttributesMiddleware::class);
    }
}

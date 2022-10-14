<?php

namespace Uzbek\LaravelValidationAttributes\Facades;

use Illuminate\Support\Facades\Facade;
use Uzbek\LaravelValidationAttributes\LaravelValidationAttributes;

/**
 * @see \Uzbek\LaravelValidationAttributes\LaravelValidationAttributes
 *
 * @method static array validationsList()
 */
class ValidationAttributes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelValidationAttributes::class;
    }
}

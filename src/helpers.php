<?php

use Uzbek\LaravelValidationAttributes\LaravelValidationAttributes;

if (!function_exists('validated')) {
    function validated(string $key = null, $default = null)
    {
        if ($key === null) {
            return LaravelValidationAttributes::$validated;
        }

        return LaravelValidationAttributes::$validated[$key] ?? $default;
    }
}


if (!function_exists('attribute_rules')) {
    function attribute_rules(): array
    {
        return LaravelValidationAttributes::$rules;
    }
}


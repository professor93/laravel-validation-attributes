<?php

// config for Uzbek/LaravelValidationAttributes
return [
    /*
     *  Automatic registration of validation will only happen if this setting is `true`
     */
    'enabled' => true,

    /*
     *  Cache key name for caching attributes
     */
    'cache_name' => 'laravel-validation-attributes',

    /*
     *  Cache expiry time
     */
    'cache_time' => 10,

    /*
     * Controllers in these directories that have validation attributes
     * will automatically be registered.
     */
    'directories' => [
        app_path('Http/Controllers'),
    ],
];

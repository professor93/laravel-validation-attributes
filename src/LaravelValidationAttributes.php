<?php

namespace Uzbek\LaravelValidationAttributes;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;
use Uzbek\LaravelAttributeScanner\LaravelAttributeScanner;
use Uzbek\LaravelValidationAttributes\Attributes\Validator;
use Uzbek\LaravelValidationAttributes\Attributes\Validators;

class LaravelValidationAttributes
{
    public static array $validated = [];
    public static array $rules = [];
    public function validationsList(): array
    {
        return Cache::remember(config('validation-attributes.cache_name', 'laravel-validation-attributes'), config('validation-attributes.cache_time', 60), function () {
            $items = [];
            try {
                $scanner = new LaravelAttributeScanner(config('validation-attributes.directories'));
                $attributes = $scanner->getAttributes([Validator::class, Validators::class], true);
            } catch (Throwable $e) {
                Log::error('Error on loading validation attributes. >>> '.$e->getMessage());

                return [];
            }

            foreach ($attributes as $key => $single) {
                if (! array_key_exists($key, $items)) {
                    $items[$key] = [];
                }

                foreach ($single as $item) {
                    $rkey = array_shift($item['arguments']);
                    $rval = array_shift($item['arguments']);

                    if (is_string($rkey) && ! empty($rval)) {
                        $rule = [$rkey => $rval];
                    } elseif (is_array($rkey)) {
                        $rule = $rkey;
                    } else {
                        $rule = [];
                    }

                    $items[$key] = array_merge($items[$key], $rule);
                }
            }

            return $items;
        });
    }
}

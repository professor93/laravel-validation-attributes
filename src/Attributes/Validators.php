<?php

namespace Uzbek\LaravelValidationAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Validators
{
    public array $items = [];

    public function __construct(array $rules)
    {
        if (count($rules) > 0) {
            foreach ($rules as $key => $rule) {
                if (is_string($key) && (is_string($rule) || (is_array($rule) && count($rule) > 0))) {
                    $this->items[] = new Validator($key, $rule);
                } elseif (count($rule) === 2 && is_string($rule[0]) && (is_string($rule[1]) || is_array($rule[1]))) {
                    $this->items[] = new Validator($rule[0], $rule[1]);
                }
            }
        }
    }
}

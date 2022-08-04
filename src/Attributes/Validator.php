<?php

namespace Uzbek\LaravelValidationAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Validator
{
    public function __construct(public string $name, public string|array $rules)
    {
    }
}

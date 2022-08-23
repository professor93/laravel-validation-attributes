<?php

namespace Uzbek\LaravelValidationAttributes;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionMethod;
use Uzbek\LaravelValidationAttributes\Attributes\Validator;
use Uzbek\LaravelValidationAttributes\Attributes\Validators;
use function WyriHaximus\listClassesInDirectories;

class ValidationAttributesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('validation-attributes.enabled', false) === true) {
            $validations = $this->validationsList();
            if (isset($validations[$request->route()->getActionName()])) {
                $rules = $validations[$request->route()->getActionName()];
                $request->validate($rules);
            }
        }

        return $next($request);
    }

    public function validationsList(): array
    {
        return Cache::remember(config('validation-attributes.cache_name', 'laravel-validation-attributes'), config('validation-attributes.cache_time', 10), function () {
            $classes = listClassesInDirectories(...config('validation-attributes.directories'));
            $items = [];

            foreach ($classes as $class) {
                $reflection = new ReflectionClass($class);
                $classMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

                foreach ($classMethods as $classMethod) {
                    if ($classMethod->class === $reflection->name) {
                        $rules = [];

                        foreach ($classMethod->getAttributes(Validator::class) as $attribute) {
                            $rules[] = $attribute->newInstance();
                        }

                        foreach ($classMethod->getAttributes(Validators::class) as $attribute) {
                            foreach ($attribute->newInstance()->items as $rule) {
                                $rules[] = $rule;
                            }
                        }

                        if (count($rules) > 0) {
                            $rl = [];
                            foreach ($rules as $rule) {
                                $rl[$rule->name] = $rule->rules;
                            }
                            $items[$classMethod->class.'@'.$classMethod->name] = $rl;
                        }
                    }
                }
            }

            return $items;
        });
    }
}

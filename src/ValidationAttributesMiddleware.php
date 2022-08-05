<?php

namespace Uzbek\LaravelValidationAttributes;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;
use Uzbek\LaravelValidationAttributes\Attributes\Validator;
use Uzbek\LaravelValidationAttributes\Attributes\Validators;

class ValidationAttributesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse)  $next
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
            $classes = $this->getAllNameSpaces(config('validation-attributes.directories'));
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
                            $items[$classMethod->class . '@' . $classMethod->name] = $rl;
                        }
                    }
                }
            }

            return $items;
        });
    }

    public function getAllNameSpaces($path): array
    {
        $filenames = $this->getFilenames($path);
        $namespaces = [];
        foreach ($filenames as $filename) {
            $namespaces[] = $this->getFullNamespace($filename) . '\\' . $this->getClassName($filename);
        }
        return $namespaces;
    }

    private function getFilenames($dirs): array
    {
        $filenames = [];
        if (is_array($dirs)) {
            foreach ($dirs as $dir) {
                $filenames = array_merge($filenames, $this->getFilenames($dir));
            }
        } else {
            $finder = new Finder();
            $finder->files()->in($dirs)->name('*.php');
            foreach ($finder as $file) {
                $filenames[] = $file->getRealPath();
            }
        }

        return $filenames;
    }

    private function getFullNamespace($filename)
    {
        $lines = file($filename);
        $array = preg_grep('/^namespace /', $lines);
        $namespaceLine = array_shift($array);
        $match = [];
        preg_match('/^namespace (.*);$/', $namespaceLine, $match);
        $fullNamespace = array_pop($match);

        return $fullNamespace;
    }

    private function getClassName($filename): ?string
    {
        $directoriesAndFilename = explode('/', $filename);
        $filename = array_pop($directoriesAndFilename);
        $nameAndExtension = explode('.', $filename);
        $className = array_shift($nameAndExtension);
        return $className;
    }
}


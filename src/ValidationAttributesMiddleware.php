<?php

namespace Uzbek\LaravelValidationAttributes;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Uzbek\LaravelValidationAttributes\Facades\ValidationAttributes;

class ValidationAttributesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('validation-attributes.enabled', false) === true) {
            $validations = ValidationAttributes::validationsList();
            if (isset($validations[$request->route()->getActionName()])) {
                LaravelValidationAttributes::$rules = $validations[$request->route()->getActionName()];
                LaravelValidationAttributes::$validated = $request->validate(LaravelValidationAttributes::$rules);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        // Validation for postal codes
        Validator::extend('postalcode', function ($attribute, $value, $parameters, $validator) {
            $value = preg_replace('/\s+/', '', $value);

            return (bool)preg_match('/^[a-zA-Z0-9]{3,10}$/', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

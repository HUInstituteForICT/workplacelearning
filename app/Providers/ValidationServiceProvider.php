<?php

namespace App\Providers;

use Illuminate\Http\Request;
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
        // Application specific validation rules
        Validator::extend('postalcode', 'App\Validators\PostalValidator@validate');
        Validator::extend('dateInWplp', 'App\Validators\DateInLearningPeriodValidator@validate');
        Validator::extend('canChain', 'App\Validators\ChainValidator@validate');

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

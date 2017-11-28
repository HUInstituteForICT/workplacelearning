<?php

namespace App\Providers;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        if (!\App::environment('debug')) {
            \URL::forceScheme('https');
        }

        // Require annotation manually
        require_once app_path('Tips/DataUnitAnnotation.php');
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

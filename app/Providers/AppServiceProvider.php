<?php

namespace App\Providers;

use App\Repository\Eloquent\LikeRepository;
use App\Repository\LikeRepositoryInterface;
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
        $this->app->bind(
            LikeRepositoryInterface::class,
            LikeRepository::class
        );
    }
}

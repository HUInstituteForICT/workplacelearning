<?php

namespace App\Providers;

use App\Repository\Eloquent\LikeRepository;
use App\Repository\LikeRepositoryInterface;
use App\Tips\DataCollectors\Collector;
use App\WorkplaceLearningPeriod;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

        $this->app->bind(Collector::class, function(Container $app) {
            $request = $app->make(Request::class);


//            if (!$request->has('year') || !$request->has('month')) {
//                throw new \RuntimeException('Missing required parameters year and month');
//            }

            $year = $request->get('year') === 'all' ? null : $request->get('year', null);
            $month = $request->get('month') === 'all' ? null : $request->get('month', null);

            $learningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod() ?? new WorkplaceLearningPeriod();

            return new Collector($year, $month, $learningPeriod);
        });
    }
}

<?php

namespace App\Providers;

use App\ChainManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use App\Repository\Eloquent\LikeRepository;
use App\Repository\Eloquent\StudentTipViewRepository;
use App\Repository\LikeRepositoryInterface;
use App\Repository\StudentTipViewRepositoryInterface;
use App\Tips\DataCollectors\Collector;
use App\Tips\PeriodMomentCalculator;
use App\WorkplaceLearningPeriod;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!\App::environment('debug')) {
            \URL::forceScheme('https');
        }

        // Require annotation manually
        require_once app_path('Tips/DataCollectors/DataUnitAnnotation.php');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChainManager::class, function (Container $app) {
            $request = $app->make(Request::class);

            return new ChainManager($request->user()->getCurrentWorkplaceLearningPeriod());
        });

        $this->app->bind(
            LikeRepositoryInterface::class,
            LikeRepository::class
        );
        // Bind repository interfaces
        $this->app->bind(LikeRepositoryInterface::class, LikeRepository::class);
        $this->app->bind(StudentTipViewRepositoryInterface::class, StudentTipViewRepository::class);

        $this->app->bind(Collector::class, function (Container $app) {
            $request = $app->make(Request::class);

            $year = 'all' === $request->get('year') ? null : $request->get('year', null);
            $month = 'all' === $request->get('month') ? null : $request->get('month', null);

            $learningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod() ?? new WorkplaceLearningPeriod();

            return new Collector($year, $month, $learningPeriod);
        });

        $this->app->bind(PeriodMomentCalculator::class, function (Container $app) {
            $request = $app->make(Request::class);
            $learningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod() ?? new WorkplaceLearningPeriod();

            return new PeriodMomentCalculator($learningPeriod);
        });
    }
}

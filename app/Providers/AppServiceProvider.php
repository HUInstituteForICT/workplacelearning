<?php

declare(strict_types=1);

namespace App\Providers;

use App\ChainManager;
use App\Interfaces\FolderSystemServiceInterface;
use App\Interfaces\LearningSystemServiceInterface;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\StudentSystemServiceInterface;
use App\Repository\Eloquent\LikeRepository;
use App\Repository\Eloquent\StudentTipViewRepository;
use App\Repository\LikeRepositoryInterface;
use App\Repository\StudentTipViewRepositoryInterface;
use App\Services\CurrentPeriodResolver;
use App\Services\CurrentUserResolver;
use App\Services\FolderSystemServiceImpl;
use App\Services\LearningSystemServiceImpl;
use App\Services\ProgressRegistrySystemServiceImpl;
use App\Services\StudentSystemServiceImpl;
use App\Tips\PeriodMomentCalculator;
use App\Tips\Services\StatisticValueFetcher;
use App\WorkplaceLearningPeriod;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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

        Blade::component('components.card', 'card');
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

        //Bind service interfaces
        $this->app->bind(ProgressRegistrySystemServiceInterface::class, ProgressRegistrySystemServiceImpl::class);
        $this->app->bind(LearningSystemServiceInterface::class, LearningSystemServiceImpl::class);
        $this->app->bind(StudentSystemServiceInterface::class, StudentSystemServiceImpl::class);
        $this->app->bind(FolderSystemServiceInterface::class, FolderSystemServiceImpl::class);




        $this->app->bind(StatisticValueFetcher::class, function (Container $app) {
            $request = $app->make(Request::class);

            $year = $request->get('year') === 'all' ? null : $request->get('year', null);
            $month = $request->get('month') === 'all' ? null : $request->get('month', null);

            $student = $app->make(CurrentUserResolver::class)->getCurrentUser();
            $learningPeriod = $student->hasCurrentWorkplaceLearningPeriod() ? $student->getCurrentWorkplaceLearningPeriod() : new WorkplaceLearningPeriod();

            return new StatisticValueFetcher($year, $month, $learningPeriod);
        });

        $this->app->bind(PeriodMomentCalculator::class, function (Container $app) {
            return new PeriodMomentCalculator($app->make(CurrentPeriodResolver::class));
        });
    }
}

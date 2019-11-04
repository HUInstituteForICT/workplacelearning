<?php

declare(strict_types=1);

namespace App\Providers;

use App\Reflection\Models\ActivityReflection;
use App\Feedback;
use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\Policies\ActivityReflectionPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\LearningActivityPolicy;
use App\Policies\WorkplaceLearningPeriodPolicy;
use App\Policies\WorkplacePolicy;
use App\Policies\StudentPolicy;
use App\Workplace;
use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Feedback::class                  => FeedbackPolicy::class,
        LearningActivityProducing::class => LearningActivityPolicy::class,
        LearningActivityActing::class    => LearningActivityPolicy::class,
        Workplace::class                 => WorkplacePolicy::class,
        WorkplaceLearningPeriod::class   => WorkplaceLearningPeriodPolicy::class,
        ActivityReflection::class        => ActivityReflectionPolicy::class,
        Student::class                   => StudentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

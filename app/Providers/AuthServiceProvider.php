<?php

namespace App\Providers;

use App\Feedback;
use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\Policies\FeedbackPolicy;
use App\Policies\LearningActivityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Feedback::class => FeedbackPolicy::class,
        LearningActivityProducing::class => LearningActivityPolicy::class,
        LearningActivityActing::class => LearningActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\LearningActivityProducingCreated;
use App\Listeners\AttachBusyActivityToNewChain;
use App\Listeners\CreateFeedbackIfNecessary;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LearningActivityProducingCreated::class => [
            AttachBusyActivityToNewChain::class,
            CreateFeedbackIfNecessary::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}

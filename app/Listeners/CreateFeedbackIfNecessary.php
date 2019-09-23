<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LearningActivityProducingCreated;
use App\Services\Factories\FeedbackFactory;

class CreateFeedbackIfNecessary
{
    /**
     * @var FeedbackFactory
     */
    private $feedbackFactory;

    public function __construct(FeedbackFactory $feedbackFactory)
    {
        $this->feedbackFactory = $feedbackFactory;
    }

    public function handle(LearningActivityProducingCreated $event): void
    {
        $activity = $event->getActivity();

        if ($activity->status->isBusy() && !$activity->difficulty->isEasy()) {
            $this->feedbackFactory->createFeedback($activity);
        }
    }
}

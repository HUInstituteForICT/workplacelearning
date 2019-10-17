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
        $givenChance = $activity->workplaceLearningPeriod->cohort->feedback_chance;

        if ($activity->extrafeedback === 1) {
            $this->feedbackFactory->createFeedback($activity);
        } elseif ($activity->status->isBusy() && !$activity->difficulty->isEasy() && $givenChance >= random_int(1, 100)) {
            $this->feedbackFactory->createFeedback($activity);
        }
    }
}

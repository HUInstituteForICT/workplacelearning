<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Feedback;
use App\LearningActivityProducing;
use App\Repository\Eloquent\FeedbackRepository;

class FeedbackFactory
{
    /**
     * @var FeedbackRepository
     */
    private $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function createFeedback(LearningActivityProducing $learningActivityProducing): Feedback
    {
        $feedback = new Feedback();
        $feedback->learningActivityProducing()->associate($learningActivityProducing);
        $this->feedbackRepository->save($feedback);

        return $feedback;
    }
}

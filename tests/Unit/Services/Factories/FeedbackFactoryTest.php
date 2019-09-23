<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\LearningActivityProducing;
use App\Repository\Eloquent\FeedbackRepository;
use Tests\TestCase;

class FeedbackFactoryTest extends TestCase
{
    public function testCreateFeedback(): void
    {
        $feedbackRepository = $this->createMock(FeedbackRepository::class);
        $feedbackRepository->expects(self::once())->method('save');

        $learningActivityProducing = $this->createMock(LearningActivityProducing::class);

        $feedbackFactory = new FeedbackFactory($feedbackRepository);
        $feedbackFactory->createFeedback($learningActivityProducing);
    }
}

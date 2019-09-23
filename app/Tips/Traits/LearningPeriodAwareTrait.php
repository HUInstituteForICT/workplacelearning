<?php

declare(strict_types=1);

namespace App\Tips\Traits;

use App\WorkplaceLearningPeriod;

trait LearningPeriodAwareTrait
{
    /**
     * @var WorkplaceLearningPeriod
     */
    protected $learningPeriod;

    public function setLearningPeriod(WorkplaceLearningPeriod $learningPeriod): void
    {
        $this->learningPeriod = $learningPeriod;
    }
}

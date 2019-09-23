<?php

namespace App\Tips\Interfaces;

use App\WorkplaceLearningPeriod;

interface LearningPeriodAwareInterface
{
    public function setLearningPeriod(WorkplaceLearningPeriod $learningPeriod): void;
}

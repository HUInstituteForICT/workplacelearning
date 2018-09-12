<?php

namespace App\Tips\DataCollectors;

use App\WorkplaceLearningPeriod;

interface CollectorInterface
{
    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod);
}

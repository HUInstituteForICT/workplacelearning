<?php

namespace App\Interfaces;

use App\WorkplaceLearningPeriod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property WorkplaceLearningPeriod $workplaceLearningPeriod
 */
interface LearningActivityInterface
{
    public function workplaceLearningPeriod(): BelongsTo;
}

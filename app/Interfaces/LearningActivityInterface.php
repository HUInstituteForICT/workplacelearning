<?php

namespace App\Interfaces;

use App\WorkplaceLearningPeriod;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property WorkplaceLearningPeriod $workplaceLearningPeriod
 * @property DateTime                $date
 */
interface LearningActivityInterface
{
    public function workplaceLearningPeriod(): BelongsTo;
}

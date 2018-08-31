<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property WorkplaceLearningPeriod $workplaceLearningPeriod
 */
interface LearningActivityInterface
{
    public function workplaceLearningPeriod(): BelongsTo;
}

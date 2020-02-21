<?php

declare(strict_types=1);

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

    public function getDescription(): string;
}

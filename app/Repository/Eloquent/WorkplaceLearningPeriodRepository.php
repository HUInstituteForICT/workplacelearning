<?php

namespace App\Repository\Eloquent;

use App\WorkplaceLearningPeriod;

class WorkplaceLearningPeriodRepository
{
    public function get(int $id): WorkplaceLearningPeriod
    {
        return WorkplaceLearningPeriod::findOrFail($id);
    }

    public function save(WorkplaceLearningPeriod $workplaceLearningPeriod): bool
    {
        return $workplaceLearningPeriod->save();
    }
}

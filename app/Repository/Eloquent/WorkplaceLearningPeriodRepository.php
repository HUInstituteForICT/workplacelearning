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

    public function update(WorkplaceLearningPeriod $wplPeriod, array $data): bool
    {
        $wplPeriod->startdate = $data['startdate'];
        $wplPeriod->enddate = $data['enddate'];
        $wplPeriod->nrofdays = $data['numdays'];
        $wplPeriod->description = $data['internshipAssignment'];

        return $wplPeriod->save();
    }
}

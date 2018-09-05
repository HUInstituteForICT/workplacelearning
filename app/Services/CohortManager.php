<?php

namespace App\Services;

use App\Cohort;
use Illuminate\Support\Facades\DB;

class CohortManager
{
    public function deleteCohort(Cohort $cohort): bool
    {
        if ($this->deleteRelated($cohort)) {
            return $cohort->delete();
        }

        return false;
    }

    private function deleteRelated(Cohort $cohort): bool
    {
        try {
            DB::transaction(function () use ($cohort) {
                $cohort->categories()->delete();
                $cohort->competencies()->delete();
                $cohort->competenceDescription()->delete();
                $cohort->resourcePersons()->delete();
                $cohort->timeslots()->delete();
                $cohort->tips()->detach();
            });
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}

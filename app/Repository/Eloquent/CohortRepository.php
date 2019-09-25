<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Cohort;
use App\Student;

class CohortRepository
{
    public function get(int $id): Cohort
    {
        return Cohort::findOrFail($id);
    }

    public function save(Cohort $cohort): bool
    {
        return $cohort->save();
    }

    public function cohortsAvailableForStudent(Student $student): array
    {
        return $student->educationProgram
            ->cohorts()
            ->where('disabled', '=', 0)->get()->all();
    }
}

<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Competence;
use App\Student;

class CompetenceRepository
{
    public function get(int $id): Competence
    {
        return (new Competence())::findOrFail($id);
    }

    public function save(Competence $competence): bool
    {
        return $competence->save();
    }

    /**
     * @return Competence[]
     */
    public function competenciesAvailableToStudent(Student $student): array
    {
        return $student->currentCohort()->competencies()->get()->all();
    }
}

<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\CompetenceDescription;
use App\Student;

class CompetenceDescriptionRepository
{
    public function get(int $id): CompetenceDescription
    {
        return (new CompetenceDescription())::findOrFail($id);
    }

    public function save(CompetenceDescription $competenceDescription): bool
    {
        return $competenceDescription->save();
    }

    public function applicableCompetenceDescriptionForStudent(Student $student): ?CompetenceDescription
    {
        return $student->currentCohort()->competenceDescription;
    }
}

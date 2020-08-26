<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\ResourcePerson;
use App\Student;

class ResourcePersonRepository
{
    public function get(int $id): ResourcePerson
    {
        return (new ResourcePerson())::findOrFail($id);
    }

    public function save(ResourcePerson $resourcePerson): bool
    {
        return $resourcePerson->save();
    }

    /**
     * @return ResourcePerson[]
     */
    public function resourcePersonsAvailableForStudent(Student $student): array
    {
        return $student->currentCohort()->resourcePersons()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        )->all();
    }
}

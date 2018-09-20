<?php

namespace App\Services;

use App\ResourcePerson;

class ResourcePersonFactory
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function createResourcePerson(string $label): ResourcePerson
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $resourcePerson = new ResourcePerson();
        $resourcePerson->person_label = $label;
        $resourcePerson->workplaceLearningPeriod()->associate($student->getCurrentWorkplaceLearningPeriod());
        $resourcePerson->educationProgram()->associate($student->educationProgram);
        $resourcePerson->save();

        return $resourcePerson;
    }
}

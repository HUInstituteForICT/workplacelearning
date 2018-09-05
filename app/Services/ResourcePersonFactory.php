<?php

namespace App\Services;

use App\ResourcePerson;
use App\Student;

class ResourcePersonFactory
{
    /**
     * @var Student
     */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function createResourcePerson(string $label): ResourcePerson
    {
        $resourcePerson = new ResourcePerson();
        $resourcePerson->person_label = $label;
        $resourcePerson->workplaceLearningPeriod()->associate($this->student->getCurrentWorkplaceLearningPeriod());
        $resourcePerson->educationProgram()->associate($this->student->educationProgram);
        $resourcePerson->save();

        return $resourcePerson;
    }
}

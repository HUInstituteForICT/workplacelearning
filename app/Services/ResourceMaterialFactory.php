<?php

namespace App\Services;

use App\ResourceMaterial;
use App\Student;

class ResourceMaterialFactory
{
    /**
     * @var Student
     */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function createResourceMaterial(string $label): ResourceMaterial
    {
        $resourceMaterial = new ResourceMaterial();
        $resourceMaterial->rm_label = $label;
        $resourceMaterial->workplaceLearningPeriod()->associate($this->student->getCurrentWorkplaceLearningPeriod());
        $resourceMaterial->save();

        return $resourceMaterial;
    }
}

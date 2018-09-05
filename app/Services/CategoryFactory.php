<?php

namespace App\Services;

use App\Category;
use App\Student;

class CategoryFactory
{
    /**
     * @var Student
     */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function createCategory(string $label): Category
    {
        $category = new Category();
        $category->category_label = $label;
        $category->workplaceLearningPeriod()->associate($this->student->getCurrentWorkplaceLearningPeriod());
        $category->save();

        return $category;
    }
}

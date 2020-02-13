<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Category;
use App\Student;

class CategoryRepository
{
    public function get(int $id): Category
    {
        return Category::findOrFail($id);
    }

    public function all(): array
    {
        return Category::all()->all();
    }

    public function save(Category $category): bool
    {
        return $category->save();
    }

    /**
     * @return Category[]
     */
    public function categoriesAvailableForStudent(Student $student): array
    {
        return $student->currentCohort()->categories()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->categories()->get()
        )->all();
    }
}

<?php

namespace App\Services;

use App\Category;

class CategoryFactory
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;

    public function __construct(CurrentPeriodResolver $currentPeriodResolver)
    {
        $this->currentPeriodResolver = $currentPeriodResolver;
    }

    public function createCategory(string $label): Category
    {
        $category = new Category();
        $category->category_label = $label;
        $category->workplaceLearningPeriod()->associate($this->currentPeriodResolver->getPeriod());
        $category->save();

        return $category;
    }
}

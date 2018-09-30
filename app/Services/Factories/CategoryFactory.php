<?php

namespace App\Services\Factories;

use App\Category;
use App\Services\CurrentPeriodResolver;

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

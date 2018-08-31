<?php

namespace App\Services;

use App\Category;
use Illuminate\Support\Facades\Auth;

class CategoryFactory
{
    public function createCategory(string $label): Category
    {
        $category = new Category();
        $category->category_label = $label;
        $category->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
        $category->save();

        return $category;
    }
}

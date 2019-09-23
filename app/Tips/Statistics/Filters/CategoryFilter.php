<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Filters;

use Illuminate\Database\Query\Builder;

class CategoryFilter implements Filter
{
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder): void
    {
        if (empty($this->parameters['category_label'])) {
            return;
        }

        $categories = array_map('trim', explode('||', $this->parameters['category_label']));

        $builder
            ->leftJoin('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where(function (Builder $builder) use ($categories) {
                $builder->whereIn('category_label', $categories);

                array_map(function (string $category) use ($builder) {
                    if (strpos($category, '*') !== false) {
                        $wildcardLabel = str_replace('*', '%', $category);
                        $builder->orWhere('category_label', 'LIKE', $wildcardLabel);
                    }
                }, $categories);
            });
    }
}

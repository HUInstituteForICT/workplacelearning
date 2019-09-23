<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Filters;

use Illuminate\Database\Query\Builder;

interface Filter
{
    public function filter(Builder $builder);
}

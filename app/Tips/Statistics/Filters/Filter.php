<?php


namespace App\Tips\Statistics\Filters;


use Illuminate\Database\Query\Builder;

interface Filter
{
    public function filter(Builder $builder);
}
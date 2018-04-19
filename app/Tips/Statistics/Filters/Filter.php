<?php


namespace App\Tips\Statistics\Filters;


use Doctrine\DBAL\Query\QueryBuilder;

interface Filter
{
    public function filter(QueryBuilder $builder);
}
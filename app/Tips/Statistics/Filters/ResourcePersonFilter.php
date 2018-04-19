<?php


namespace App\Tips\Statistics\Filters;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Query\Builder;

class ResourcePersonFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
    {
        if (empty($this->parameters['person_label'])) {
            return;
        }

        $labels = array_map('trim', explode('||', $this->parameters['person_label']));

        $builder
            ->leftJoin('resourceperson', 'res_person_id', '=', 'rp_id')
            ->whereIn('person_label', $labels);
    }
}
<?php


namespace App\Tips\Statistics\Filters;


use Doctrine\DBAL\Query\QueryBuilder;

class ResourcePersonFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(QueryBuilder $builder)
    {
        $builder->leftJoin('resourceperson', 'res_person_id',
            '=', 'rp_id')->where('person_label', '=', $this->parameters['person_label']);
    }
}
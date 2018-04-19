<?php


namespace App\Tips\Statistics\Filters;


use Doctrine\DBAL\Query\QueryBuilder;

class TimeslotFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(QueryBuilder $builder)
    {
        $builder->leftJoin('timeslot', 'learningactivityacting.timeslot_id', '=', 'timeslot.timeslot_id')
        ->where('timeslot_text', '=', $this->parameters['timeslot_text']);
    }
}
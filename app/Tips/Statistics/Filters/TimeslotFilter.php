<?php


namespace App\Tips\Statistics\Filters;


use Illuminate\Database\Query\Builder;

class TimeslotFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
    {
        if (empty($this->parameters['timeslot_text'])) {
            return;
        }

        $timeslots = array_map('trim', explode('||', $this->parameters['timeslot_text']));

        $builder
            ->leftJoin('timeslot', 'learningactivityacting.timeslot_id', '=', 'timeslot.timeslot_id')
            ->whereIn('timeslot_text', $timeslots);
    }
}
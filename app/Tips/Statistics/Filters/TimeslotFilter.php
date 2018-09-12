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

    public function filter(Builder $builder): void
    {
        if (empty($this->parameters['timeslot_text'])) {
            return;
        }

        $timeslots = array_map('trim', explode('||', $this->parameters['timeslot_text']));

        $builder
            ->leftJoin('timeslot', 'learningactivityacting.timeslot_id', '=', 'timeslot.timeslot_id')
            ->where(function (Builder $builder) use ($timeslots) {
                $builder->whereIn('timeslot_text', $timeslots);

                array_map(function (string $timeslot) use ($builder) {
                    if (strpos($timeslot, '*') !== false) {
                        $wildcardLabel = str_replace('*', '%', $timeslot);
                        $builder->orWhere('timeslot_text', 'LIKE', $wildcardLabel);
                    }
                }, $timeslots);
            });
    }
}

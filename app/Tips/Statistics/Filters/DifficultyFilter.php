<?php


namespace App\Tips\Statistics\Filters;


use Illuminate\Database\Query\Builder;

class DifficultyFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
    {
        if (empty($this->parameters['difficulty_label'])) {
            return;
        }

        $difficulties = array_map('trim', explode('||', $this->parameters['difficulty_label']));

        $builder
            ->leftJoin('difficulty', 'learningactivityproducing.difficulty_id', '=', 'difficulty.difficulty_id')
            ->whereIn('difficulty_label', $difficulties);
    }
}
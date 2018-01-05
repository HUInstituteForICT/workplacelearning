<?php


namespace App\Tips\DataCollectors;


use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;

abstract class AbstractCollector implements CollectorInterface
{

    /** @var string|int $year */
    protected $year;
    /** @var string|int $month */
    protected $month;
    /** @var WorkplaceLearningPeriod $learningPeriod */
    protected $learningPeriod;

    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;
    }

    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$this->year, $this->month]);
    }
}
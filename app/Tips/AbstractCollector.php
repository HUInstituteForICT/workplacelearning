<?php


namespace App\Tips;


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

    /**
     * Get the value of the dataUnit by name
     *
     * @param $dataUnitName
     * @return float|int
     */
    public function get($dataUnitName)
    {
        $method = $this->getMethod($dataUnitName);
        $parameters = $this->getOptionalParameters($dataUnitName);
        list($column, $value) = $parameters;

        return $this->{$method}($column, $value);
    }

    protected function getOptionalParameters($dataUnitName)
    {
        if(preg_match('/\[(.*?)\]/', $dataUnitName, $columnAndValue) > 0 && !empty($columnAndValue[1])) { // Check index 2 because we will have 3 matches (whole string, column, value), this check prevents empty params
            return explode("=", $columnAndValue[1]);
        }
        return [null, null];
    }


    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$this->year, $this->month]);
    }


    protected function getMethod($dataUnitName)
    {
        if(str_contains($dataUnitName, ["[", "]"])) {
            return static::$dataUnitToMethodMapping[explode("[", $dataUnitName)[0]];
        }
        return static::$dataUnitToMethodMapping[$dataUnitName];
    }
}
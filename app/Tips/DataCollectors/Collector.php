<?php


namespace App\Tips\DataCollectors;


use App\Tips\Statistics\Filters\Filter;
use App\Tips\Statistics\StatisticVariable;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;

class Collector implements CollectorInterface
{

    /** @var string|int $year */
    protected $year;
    /** @var string|int $month */
    protected $month;
    /** @var WorkplaceLearningPeriod $learningPeriod */
    protected $learningPeriod;

    /** @var PredefinedStatisticCollector $predefinedStatisticCollector */
    public $predefinedStatisticCollector;


    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;

        $this->predefinedStatisticCollector = new PredefinedStatisticCollector($year, $month, $learningPeriod);

    }

    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$this->year, $this->month]);
    }

    public function getValueForVariable(StatisticVariable $statisticVariable)
    {
        $builder = $this->learningPeriod->learningActivityActing()->getBaseQuery();

        foreach ($statisticVariable->filters as $filterData) {

            $parameters = collect($filterData['parameters'])->reduce(function ($carry, $parameter) {
                if (isset($parameter['value'])) {
                    $carry[$parameter['propertyName']] = $parameter['value'];
                }

                return $carry;
            }, []);

            /** @var Filter $filter */
            $filter = new $filterData['class']($parameters);

            $filter->filter($builder);
        }

        return $this->wherePeriod($builder)->count();
    }
}
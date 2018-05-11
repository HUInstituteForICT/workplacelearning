<?php


namespace App\Tips\DataCollectors;


use App\Tips\Statistics\Filters\Filter;
use App\Tips\Statistics\Filters\ResourcePersonFilter;
use App\Tips\Statistics\StatisticVariable;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;

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

    /**
     * @param StatisticVariable $statisticVariable
     * @throws InvalidArgumentException On invalid filter use
     * @return int|mixed
     */
    public function getValueForVariable(StatisticVariable $statisticVariable)
    {
        // Get the base query from the correct Model
        if($statisticVariable->statistic->education_program_type === 'acting') {
            $builder = $this->learningPeriod->learningActivityActing()->getBaseQuery();
        } elseif($statisticVariable->statistic->education_program_type === 'producing') {
            $builder = $this->learningPeriod->learningActivityProducing()->getBaseQuery();
        }


        // Apply each filter
        foreach ($statisticVariable->filters as $filterData) {

            // Get an array of the parameters for the filter
            $parameters = collect($filterData['parameters'])->reduce(function ($carry, $parameter) {
                if (isset($parameter['value'])) {
                    $carry[$parameter['propertyName']] = $parameter['value'];
                }

                return $carry;
            }, []);
            
            if(!\in_array(Filter::class, class_implements($filterData['class']), true)) {
                throw new InvalidArgumentException('Invalid filter found');
            }

            /** @var Filter $filter */
            $filter = new $filterData['class']($parameters);

            $filter->filter($builder);
        }



        // Hours select can onle be used on a statistic variable
        if($statisticVariable->selectType === 'hours' && $statisticVariable->type === 'producing') {
            return $this->wherePeriod($builder)->sum('duration');
        }

        return $this->wherePeriod($builder)->count();


    }
}
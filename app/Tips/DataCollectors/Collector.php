<?php

namespace App\Tips\DataCollectors;

use App\Tips\Models\StatisticVariable;
use App\Tips\Statistics\Filters\Filter;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;

class Collector
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

        $this->predefinedStatisticCollector = new PredefinedStatisticCollector($this->year, $this->month,
            $this->learningPeriod);
    }

    protected function applyPeriod(Builder $queryBuilder)
    {
        if (null === $this->year || null === $this->month) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$this->year, $this->month]);
    }

    private function getQueryBuilder(string $educationProgramType): Builder
    {
        // Get the base query from the correct Model
        if ('acting' === $educationProgramType) {
            return $this->learningPeriod->learningActivityActing()->getBaseQuery();
        }

        if ('producing' === $educationProgramType) {
            return $this->learningPeriod->learningActivityProducing()->getBaseQuery();
        }

        throw new \RuntimeException('Invalid educationProgramType; no matching LearningActivity');
    }

    private function applyFilters(Builder $builder, array $filters): void
    {
        array_walk($filters, function ($filterData) use ($builder): void {
            // Get an array of the parameters for the filter
            $parameters = collect($filterData['parameters'])->reduce(function ($carry, $parameter) {
                if (isset($parameter['value'])) {
                    $carry[$parameter['propertyName']] = $parameter['value'];
                }

                return $carry;
            }, []);

            if (!\in_array(Filter::class, class_implements($filterData['class']), true)) {
                throw new InvalidArgumentException('Invalid filter');
            }

            /** @var Filter $filter */
            $filter = new $filterData['class']($parameters);

            $filter->filter($builder);
        });
    }

    /**
     * @return int
     */
    public function getValueForVariable(StatisticVariable $statisticVariable, string $type): float
    {
        $builder = $this->getQueryBuilder($type);

        $this->applyFilters($builder, $statisticVariable->filters);

        $this->applyPeriod($builder);

        // Hours select can onle be used on a statistic variable
        if ('hours' === $statisticVariable->selectType && 'producing' === $statisticVariable->type) {
            return $builder->sum('duration');
        }

        return $builder->count();
    }
}

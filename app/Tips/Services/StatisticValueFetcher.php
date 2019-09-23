<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Tips\Models\StatisticVariable;
use App\Tips\Statistics\Filters\Filter;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;

class StatisticValueFetcher
{
    /** @var string|int $year */
    public $year;
    /** @var string|int $month */
    public $month;
    /** @var WorkplaceLearningPeriod $learningPeriod */
    public $learningPeriod;

    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;
    }

    protected function applyPeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$this->year, $this->month]);
    }

    private function getQueryBuilder(string $educationProgramType): Builder
    {
        // Get the base query from the correct Model
        if ($educationProgramType === 'acting') {
            return $this->learningPeriod->learningActivityActing()->getBaseQuery();
        }

        if ($educationProgramType === 'producing') {
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

    public function getValueForVariable(StatisticVariable $statisticVariable, string $type): float
    {
        $builder = $this->getQueryBuilder($type);

        $this->applyFilters($builder, $statisticVariable->filters);
        $this->applyPeriod($builder);

        // Hours select can onle be used on a statistic variable
        if ($statisticVariable->selectType === 'hours' && $statisticVariable->type === 'producing') {
            return $builder->sum('duration');
        }

        return $builder->count();
    }
}

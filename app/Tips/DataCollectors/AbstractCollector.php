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

    /** @var ProducingPredefinedStatisticCollector | ActingPredefinedStatisticCollector $predefinedStatisticCollector */
    public $predefinedStatisticCollector;

    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;

        if(static::class === ActingCollector::class) {
            $this->predefinedStatisticCollector = new ActingPredefinedStatisticCollector($year, $month, $learningPeriod);
        } elseif(static::class === ProducingCollector::class) {
            $this->predefinedStatisticCollector = new ProducingPredefinedStatisticCollector($year, $month, $learningPeriod);
        }
    }

    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$this->year, $this->month]);
    }
}
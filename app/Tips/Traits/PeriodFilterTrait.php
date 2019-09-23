<?php

declare(strict_types=1);

namespace App\Tips\Traits;

use Illuminate\Database\Query\Builder;

trait PeriodFilterTrait
{
    protected $year;
    protected $month;

    public function setYearAndMonth($year, $month): void
    {
        $this->year = $year;
        $this->month = $month;
    }

    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$this->year, $this->month]);
    }
}

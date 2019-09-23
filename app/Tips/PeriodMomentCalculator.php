<?php

declare(strict_types=1);

namespace App\Tips;

use App\Services\CurrentPeriodResolver;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;

class PeriodMomentCalculator
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;

    /**
     * @var WorkplaceLearningPeriod|null
     */
    private $workplaceLearningPeriod;

    public function __construct(CurrentPeriodResolver $currentPeriodResolver)
    {
        $this->currentPeriodResolver = $currentPeriodResolver;
    }

    public function getMomentAsPercentage(): string
    {
        if ($this->workplaceLearningPeriod === null) {
            $this->workplaceLearningPeriod = $this->currentPeriodResolver->getPeriod();
        }

        $startDate = new Carbon($this->workplaceLearningPeriod->startdate);
        $endDate = new Carbon($this->workplaceLearningPeriod->enddate);
        $currentDate = new Carbon();

        $daysInPeriod = $currentDate->diffInDays($startDate);
        $totalDays = $startDate->diffInDays($endDate);

        if ($totalDays > 0) {
            $percentage = $daysInPeriod / $totalDays;
        } else {
            $percentage = 0;
        }

        if ($percentage > 1) { // Use 100% whenever the WPLP is completed
            $percentage = 1;
        }

        return number_format($percentage * 100);
    }
}

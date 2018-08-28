<?php


namespace App\Tips;

use App\WorkplaceLearningPeriod;
use Carbon\Carbon;

class PeriodMomentCalculator
{
    /**
     * @var WorkplaceLearningPeriod
     */
    private $workplaceLearningPeriod;

    public function __construct(WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        $this->workplaceLearningPeriod = $workplaceLearningPeriod;
    }

    public function getMomentAsPercentage(): string
    {
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

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

    public function getMomentAsPercentage(): int
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

        return number_format($percentage * 100);
    }
}
<?php


namespace App\Tips\Statistics\Predefined;


use App\Timeslot;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

/**
 * In this class the Category represents a timeslot (acting's type of categories)
 */
class LeastUsedCategory extends BasePredefinedStatistic
{

    public function getName(): string
    {
        return 'Percentage the least used category is used';
    }

    public function getResultDescription(): string
    {
        return 'The category\'s name';
    }

    public function calculate(): Resultable
    {
        $leastUsedCategory = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()
                ->selectRaw('timeslot_id, COUNT(timeslot_id) as count')
                ->groupBy('timeslot_id')
                ->orderBy('count', 'ASC')
                ->limit(1)
                ->getBaseQuery()
        )->first();

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();

        if ($totalActivities === 0 || !$leastUsedCategory) {
            return new InvalidStatisticResult();
        }

        $timeslot = Timeslot::find($leastUsedCategory->timeslot_id);

        $percentage = $leastUsedCategory->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $timeslot->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}
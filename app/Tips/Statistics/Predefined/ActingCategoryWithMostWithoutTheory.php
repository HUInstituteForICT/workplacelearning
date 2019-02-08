<?php


namespace App\Tips\Statistics\Predefined;


use App\Timeslot;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

/**
 * In this class the Category represents a timeslot (acting's type of categories)
 */
class ActingCategoryWithMostWithoutTheory extends BasePredefinedStatistic
{

    public function getName(): string
    {
        return 'Category with the most activities without theory';
    }

    public function getResultDescription(): string
    {
        return 'The category\'s name';
    }

    public function calculate(): Resultable
    {
        $categoryData = $this->wherePeriod(

            $this->learningPeriod->learningActivityActing()
                ->selectRaw('timeslot_id, COUNT(timeslot_id) as count')
                ->whereNull('res_material_id')
                ->limit(1)
                ->orderBy('count', 'DESC')
                ->groupBy('timeslot_id')
                ->getBaseQuery()
        )->first();

        if (!$categoryData) {
            return new InvalidStatisticResult();
        }

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityActing()->where('timeslot_id', '=',
            $categoryData->timeslot_id)->getBaseQuery())->count();

        if ($totalActivities === 0 || !$categoryData) {
            return new InvalidStatisticResult();
        }

        $timeslot = Timeslot::find($categoryData->timeslot_id);

        $percentage = $categoryData->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $timeslot->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}
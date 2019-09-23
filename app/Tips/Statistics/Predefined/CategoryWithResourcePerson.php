<?php

namespace App\Tips\Statistics\Predefined;

use App\Timeslot;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

/**
 * In this class the Category represents a timeslot (acting's type of categories).
 */
class CategoryWithResourcePerson extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Category done mostly with one resource person';
    }

    public function getResultDescription(): string
    {
        return 'The category\'s name';
    }

    public function calculate(): Resultable
    {
        $differentCategories = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->selectRaw('timeslot_id, COUNT(timeslot_id) as count')->groupBy('timeslot_id')
                ->getBaseQuery()
        )->get()->toArray();
        array_walk($differentCategories, function ($categoryData) {
            $combinationsForThisCategory = $this->wherePeriod($this->learningPeriod->learningActivityActing()
                ->selectRaw('timeslot_id, res_person_id, COUNT(timeslot_id) as combinationCount')
                ->where('timeslot_id', '=', $categoryData->timeslot_id)
                ->groupBy(['timeslot_id', 'res_person_id'])
                ->getBaseQuery())
                ->get()->toArray();

            // Get the combination (category, res person) that occurs most often percentage wise
            foreach ($combinationsForThisCategory as $combo) {
                $percentageWithThisCombo = $combo->combinationCount / $categoryData->count;

                if (!isset($categoryData->bestCombo) || $categoryData->bestCombo->percentage < $percentageWithThisCombo) {
                    $combo->percentage = $percentageWithThisCombo;
                    $categoryData->bestCombo = $combo;
                }
            }
        });

        $highestPercentageCombo = null;

        foreach ($differentCategories as $category) {
            if ($highestPercentageCombo === null || $category->bestCombo->percentage > $highestPercentageCombo->percentage) {
                $highestPercentageCombo = $category->bestCombo;
            }
        }

        if ($highestPercentageCombo === null) {
            return new InvalidStatisticResult();
        }

        $timeslot = Timeslot::find($highestPercentageCombo->timeslot_id);

        if (!$timeslot) {
            return new InvalidStatisticResult();
        }

        return new StatisticCalculationResult($highestPercentageCombo->percentage, $timeslot->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}

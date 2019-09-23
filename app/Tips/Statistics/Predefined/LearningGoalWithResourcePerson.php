<?php

namespace App\Tips\Statistics\Predefined;

use App\LearningGoal;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class LearningGoalWithResourcePerson extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Learning goal done mostly with one resource person';
    }

    public function getResultDescription(): string
    {
        return 'The learning goal\'s label';
    }

    public function calculate(): Resultable
    {
        $differentCategories = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->selectRaw('learninggoal_id, COUNT(learninggoal_id) as count')->groupBy('learninggoal_id')
                ->getBaseQuery()
        )->get()->toArray();

        array_walk($differentCategories, function ($categoryData) {
            $combinationsForThisCategory = $this->wherePeriod(
                $this->learningPeriod->learningActivityActing()
                    ->selectRaw('learninggoal_id, res_person_id, COUNT(learninggoal_id) as combinationCount')
                    ->where('learninggoal_id', '=', $categoryData->learninggoal_id)
                    ->groupBy(['learninggoal_id', 'res_person_id'])
                    ->getBaseQuery())
                ->get()->toArray();

            // Get the combination (learninggoal, res person) that occurs most often percentage wise
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

        $learninggoal = LearningGoal::find($highestPercentageCombo->learninggoal_id);

        if (!$learninggoal) {
            return new InvalidStatisticResult();
        }

        return new StatisticCalculationResult($highestPercentageCombo->percentage, $learninggoal->learninggoal_label);
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}

<?php


namespace App\Tips\DataCollectors;


use App\LearningGoal;
use App\Tips\DataUnitAnnotation;
use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticCalculationResultCollection;
use Illuminate\Database\Query\Builder;

class ActingPredefinedStatisticCollector extends AbstractCollector
{
    /**
     * @DataUnitAnnotation(name="Percentage learning moments for every learning question without use of theory", method="percentageLearningMomentsWithoutTheory", valueParameterDescription="The names of the learning moments")
     * @return StatisticCalculationResultCollection
     * @throws \Exception
     */
    public function percentageLearningMomentsWithoutTheory()
    {
        $resultCollection = new StatisticCalculationResultCollection();

        $learningQuestions = $this->learningPeriod->learningGoals;
        $this->learningPeriod->learningActivityActing;

        $learningQuestions->each(function (LearningGoal $goal) use ($resultCollection) {

            $totalCount = $this->wherePeriod(
                $this->learningPeriod->learningActivityActing()->where('learninggoal_id', '=', $goal->learninggoal_id)->getBaseQuery()
            )->count();

            $noTheoryCount = $this->wherePeriod($this->learningPeriod->learningActivityActing()
                ->where('learninggoal_id', '=', $goal->learninggoal_id)
                ->whereNull('res_material_id')
                ->getBaseQuery()
            )->count();

            if($totalCount > 0) {
                $resultCollection->addResult(new StatisticCalculationResult(($noTheoryCount / $totalCount), $goal->learninggoal_label));
            }
        });

        return $resultCollection;
    }

}
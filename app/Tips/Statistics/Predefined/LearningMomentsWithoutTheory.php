<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Predefined;

use App\LearningGoal;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticResultCollection;

class LearningMomentsWithoutTheory extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Percentage learning moments for every learning question without use of theory';
    }

    public function calculate(): Resultable
    {
        $resultCollection = new StatisticResultCollection(true);

        $learningQuestions = $this->learningPeriod->learningGoals;
        $this->learningPeriod->learningActivityActing;

        $learningQuestions->each(function (LearningGoal $goal) use ($resultCollection): void {
            $totalCount = $this->wherePeriod(
                $this->learningPeriod->learningActivityActing()->where('learninggoal_id', '=',
                    $goal->learninggoal_id)->getBaseQuery()
            )->count();

            $noTheoryCount = $this->wherePeriod($this->learningPeriod->learningActivityActing()
                ->where('learninggoal_id', '=', $goal->learninggoal_id)
                ->whereNull('res_material_id')
                ->getBaseQuery()
            )->count();

            if ($totalCount > 0) {
                $resultCollection->addResult(new StatisticCalculationResult($noTheoryCount / $totalCount,
                    $goal->learninggoal_label));
            }
        });

        return $resultCollection;
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }

    public function getResultDescription(): string
    {
        return 'The comma separated names of the learning moments (e.g. \'Unplanned moment, individual session\')';
    }
}

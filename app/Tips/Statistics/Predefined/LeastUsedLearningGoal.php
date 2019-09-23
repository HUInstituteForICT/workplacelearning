<?php

namespace App\Tips\Statistics\Predefined;

use App\LearningGoal;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class LeastUsedLearningGoal extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Percentage the least used learning goal is used';
    }

    public function getResultDescription(): string
    {
        return 'The learning goal\'s label';
    }

    public function calculate(): Resultable
    {
        $leastUsedLearninggoal = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()
                ->selectRaw('learninggoal_id, COUNT(learninggoal_id) as count')
                ->groupBy('learninggoal_id')
                ->orderBy('count', 'ASC')
                ->limit(1)
                ->getBaseQuery()
        )->first();

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();

        if ($totalActivities === 0 || !$leastUsedLearninggoal) {
            return new InvalidStatisticResult();
        }

        $learningGoal = LearningGoal::find($leastUsedLearninggoal->learninggoal_id);

        $percentage = $leastUsedLearninggoal->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $learningGoal->learninggoal_label);
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}

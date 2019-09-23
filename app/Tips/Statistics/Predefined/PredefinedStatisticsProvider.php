<?php

namespace App\Tips\Statistics\Predefined;

class PredefinedStatisticsProvider
{
    public static function getPredefinedStatisticClassNames(): array
    {
        return [
            LearningMomentsWithoutTheory::class,
            CategoryWithHighestDifficulty::class,
            PersonWithEasiestDifficulty::class,
            CategoryWithResourcePerson::class,
            LearningGoalWithResourcePerson::class,
            LeastUsedLearningGoal::class,
            LeastUsedCategory::class,
            LeastUsedResourcePerson::class,
            MostUsedCategory::class,
            ActingCategoryWithMostWithoutTheory::class,
            ProducingCategoryWithMostWithoutTheory::class,
        ];
    }
}

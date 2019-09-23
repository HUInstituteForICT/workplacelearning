<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Predefined;

use App\Category;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class MostUsedCategory extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Percentage the most used category is used';
    }

    public function getResultDescription(): string
    {
        return 'The category\'s name';
    }

    public function calculate(): Resultable
    {
        $mostUsedCategory = $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()
                ->selectRaw('category_id, COUNT(category_id) as count')
                ->groupBy('category_id')
                ->orderBy('count', 'DESC')
                ->limit(1)
                ->getBaseQuery()
        )->first();

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityProducing()->getBaseQuery())->count();

        if ($totalActivities === 0 || !$mostUsedCategory) {
            return new InvalidStatisticResult();
        }

        $category = Category::find($mostUsedCategory->category_id);

        $percentage = $mostUsedCategory->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $category->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::PRODUCING_TYPE;
    }
}

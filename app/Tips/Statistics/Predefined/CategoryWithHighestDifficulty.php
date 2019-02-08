<?php


namespace App\Tips\Statistics\Predefined;


use App\Category;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticResult;

class CategoryWithHighestDifficulty extends BasePredefinedStatistic
{

    public function getName(): string
    {
        return 'Category with highest difficulty';
    }

    public function getResultDescription(): string
    {
        return 'The found category\'s name';
    }

    public function calculate(): Resultable
    {
        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('category_id, AVG(difficulty_id) as category_difficulty')
            ->groupBy('category_id')
            ->orderBy('category_difficulty')->limit(1)->getBaseQuery())->first();

        if ($result !== null && !empty($result->category_id) && !empty($result->category_difficulty)) {
            $category = Category::find($result->category_id);
        } else {
            return new InvalidStatisticResult();
        }

        return new StatisticResult((float)$result->category_difficulty, $category->localizedLabel());
    }



    public function getEducationProgramType(): string
    {
        return self::PRODUCING_TYPE;
    }
}
<?php


namespace App\Tips;


use App\Category;
use App\Tips\Statistics\StatisticCalculationResult;
use App\WorkplaceLearningPeriod;
use Illuminate\Support\Facades\DB;

class ProducingPredefinedStatisticCollector extends AbstractCollector
{

    /**
     * @DataUnitAnnotation(name="Category with highest difficulty", method="categoryWithHighestDifficulty"))
     * @return StatisticCalculationResult
     * @throws \Exception
     */
    public function categoryWithHighestDifficulty() {

        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('category_id, AVG(difficulty_id) as category_difficulty')
            ->groupBy('category_id')
            ->orderBy('category_difficulty')->limit(1)->getBaseQuery())->first();


        if (!empty($result->category_id) && !empty($result->category_difficulty)) {
            $category = (new Category)->find($result->category_id);
        } else {
            throw new \Exception("Unable to get category id");
        }

        return new StatisticCalculationResult($result->category_difficulty, $category->category_label);
    }

}
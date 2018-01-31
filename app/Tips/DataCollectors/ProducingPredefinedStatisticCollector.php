<?php


namespace App\Tips\DataCollectors;


use App\Category;
use App\ResourcePerson;
use App\Tips\DataUnitAnnotation;
use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticCalculationResultCollection;

class ProducingPredefinedStatisticCollector extends AbstractCollector
{

    /**
     * @DataUnitAnnotation(name="Category with highest difficulty", method="categoryWithHighestDifficulty", valueParameterDescription="The found category's name")
     * @return StatisticCalculationResultCollection
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

        $resultCollection = new StatisticCalculationResultCollection();
        $resultCollection->addResult(new StatisticCalculationResult($result->category_difficulty,
            $category->category_label));


        return $resultCollection;
    }


    /**
     * @DataUnitAnnotation(name="Person easiest to work with", method="personWithEasiestDifficulty", valueParameterDescription="The found person's name")
     * @return StatisticCalculationResultCollection
     * @throws \Exception When unable to find ResourcePerson
     */
    public function personWithEasiestDifficulty()
    {
        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('learningactivityproducing.res_person_id, ROUND(AVG(learningactivityproducing.difficulty_id) * 3.33,1) as person_difficulty')
            ->groupBy('learningactivityproducing.res_person_id')
            ->join('resourceperson', 'learningactivityproducing.res_person_id', '=', 'rp_id')
            ->whereNotIn('person_label', ['Alleen', 'alleen', 'None', 'none'])
            ->orderBy('person_difficulty')->limit(1)->getBaseQuery()
        )->first();

        if (!empty($result->res_person_id) && !empty($result->person_difficulty)) {
            /** @var ResourcePerson $person */
            $person = (new ResourcePerson)->find($result->res_person_id);
        } else {
            throw new \Exception("Unable to get person id");
        }

        $resultCollection = new StatisticCalculationResultCollection();
        $resultCollection->addResult(new StatisticCalculationResult($result->person_difficulty, $person->person_label));

        return $resultCollection;
    }

}
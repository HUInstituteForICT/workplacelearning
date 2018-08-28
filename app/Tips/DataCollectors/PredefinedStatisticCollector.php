<?php


namespace App\Tips\DataCollectors;


use App\LearningGoal;
use App\ResourcePerson;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticResult;
use App\Tips\Statistics\StatisticResultCollection;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;

class PredefinedStatisticCollector implements CollectorInterface
{

    private $year;
    private $month;
    private $learningPeriod;

    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;
    }

    protected function wherePeriod(Builder $queryBuilder)
    {
        if ($this->year === null || $this->month === null) {
            return $queryBuilder;
        }

        return $queryBuilder->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$this->year, $this->month]);
    }


    /**
     * @DataUnitAnnotation(name="Category with highest difficulty", method="categoryWithHighestDifficulty", valueParameterDescription="The found category's name", epType="Producing")
     * @return Resultable
     * @throws \Exception
     */
    public function categoryWithHighestDifficulty():Resultable {

        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('category_id, AVG(difficulty_id) as category_difficulty')
            ->groupBy('category_id')
            ->orderBy('category_difficulty')->limit(1)->getBaseQuery())->first();


        if ($result !== null && !empty($result->category_id) && !empty($result->category_difficulty)) {
            $category = (new \App\Category)->find($result->category_id);
        } else {
            throw new \RuntimeException('Unable to get category id');
        }


        return new StatisticResult((float) $result->category_difficulty, $category->category_label);
    }

    /**
     * @DataUnitAnnotation(name="Person easiest to work with", method="personWithEasiestDifficulty", valueParameterDescription="The found person's name", epType="Producing")
     * @return Resultable
     * @throws \Exception When unable to find ResourcePerson
     */
    public function personWithEasiestDifficulty(): Resultable
    {
        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('learningactivityproducing.res_person_id, ROUND(AVG(learningactivityproducing.difficulty_id) * 3.33,1) as person_difficulty')
            ->groupBy('learningactivityproducing.res_person_id')
            ->rightJoin('resourceperson', 'learningactivityproducing.res_person_id', '=', 'rp_id')
            ->whereNotIn('person_label', ['Alleen', 'alleen', 'None', 'none'])
            ->orderBy('person_difficulty')->limit(1)->getBaseQuery()
        )->first();

        if ($result !== null && !empty($result->res_person_id) && !empty($result->person_difficulty)) {
            /** @var ResourcePerson $person */
            $person = (new ResourcePerson)->find($result->res_person_id);
        } else {
            throw new \RuntimeException('Unable to get person id');
        }

        return new StatisticResult($result->person_difficulty, $person->person_label);
    }

    /**
     * @DataUnitAnnotation(name="Percentage learning moments for every learning question without use of theory", method="percentageLearningMomentsWithoutTheory", valueParameterDescription="The comma separated names of the learning moments (e.g. 'Unplanned moment, individual session')", epType="Acting")
     * @return Resultable
     * @throws \Exception
     */
    public function percentageLearningMomentsWithoutTheory(): Resultable
    {
        $resultCollection = new StatisticResultCollection(true);

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
                $resultCollection->addResult(new StatisticCalculationResult($noTheoryCount / $totalCount, $goal->learninggoal_label));
            }
        });

        return $resultCollection;
    }

}
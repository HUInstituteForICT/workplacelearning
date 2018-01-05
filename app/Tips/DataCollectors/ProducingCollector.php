<?php


namespace App\Tips\DataCollectors;


use App\Tips\DataUnitAnnotation;

class ProducingCollector extends AbstractCollector
{
    /**
     * @DataUnitAnnotation(name="Total learning activities", method="totalLearningActivities")
     * @return int
     */
    public function totalLearningActivities()
    {
        return $this->wherePeriod($this->learningPeriod->learningActivityProducing()->getBaseQuery())->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain resource person", method="activitiesWithResourcePerson", hasParameters=true, parameterName="Person label")
     * @param mixed $personLabel
     * @return int
     */
    public function activitiesWithResourcePerson($personLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()->leftJoin('resourceperson', 'res_person_id',
                '=', 'rp_id')->where('person_label', '=', $personLabel)
        )->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain category", method="activitiesWithCategory", hasParameters=true, parameterName="Category label")
     * @param $categoryLabel
     * @return int

     */
    public function activitiesWithCategory($categoryLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()->leftJoin('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
                ->where('category_label', '=', $categoryLabel)
        )->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain difficulty", method="activitiesWithDifficulty", hasParameters=true, parameterName="Difficulty label")
     * @param string $difficultyLabel
     * @return int
     */
    public function activitiesWithDifficulty($difficultyLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()->leftJoin('difficulty', 'learningactivityproducing.difficulty_id', '=', 'difficulty.difficulty_id')
                ->where('difficulty_label', '=', $difficultyLabel)
        )->count();
    }

    /**
     * @DataUnitAnnotation(name="Total hours", method="totalHours")
     * @return int
     */
    public function totalHours()
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()
        )->sum('duration');
    }

    /**
     * @DataUnitAnnotation(name="Hours with certain resource person", method="hoursWithResourcePerson", hasParameters=true, parameterName="Person label")
     * @param mixed $personLabel
     * @return int
     */
    public function hoursWithResourcePerson($personLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()->leftJoin('resourceperson', 'res_person_id',
                '=', 'rp_id')->where('person_label', '=', $personLabel)
        )->sum('duration');
    }

    /**
     * @DataUnitAnnotation(name="Hours with certain difficulty", method="hoursWithDifficulty", hasParameters=true, parameterName="Difficulty label")
     * @param string $difficultyLabel
     * @return int
     */
    public function hoursWithDifficulty($difficultyLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityProducing()->getBaseQuery()->leftJoin('difficulty', 'learningactivityproducing.difficulty_id', '=', 'difficulty.difficulty_id')
                ->where('difficulty_label', '=', $difficultyLabel)
        )->sum('duration');
    }
}
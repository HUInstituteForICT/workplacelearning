<?php


namespace App;


class ProducingAnalysis
{
    public $analysisCollector;
    public $analysisData = [];

    // Holds Chart objects lazily
    private $charts;

    private $statistics;

    private $chains;
    private $producingAnalysisChains;

    public function __construct(ProducingAnalysisCollector $analysisCollector, $year, $month)
    {
        $this->analysisCollector = $analysisCollector;
        $this->buildData($year, $month);
        $this->chains = $analysisCollector->getTaskChainsByDate(25, $year, $month);
        $this->statistics = new ProducingAnalysisStatistics($this->analysisData);
    }

    public function buildData($year, $month)
    {
        $this->analysisData['avg_difficulty'] = $this->analysisCollector->getAverageDifficultyByDate($year, $month);
        $this->analysisData['num_difficult_lap'] = $this->analysisCollector->getNumDifficultTasksByDate($year, $month);
        $this->analysisData['hours_difficult_lap'] = $this->analysisCollector->getHoursDifficultTasksByDate($year,
            $month);
        $this->analysisData['most_occuring_category'] = $this->analysisCollector->getMostOccuringCategoryByDate($year,
            $month);
        $this->analysisData['category_difficulty'] = $this->analysisCollector->getCategoryDifficultyByDate($year,
            $month);
        $this->analysisData['num_hours_alone'] = $this->analysisCollector->getNumHoursAlone($year, $month);
        $this->analysisData['category_difficulty'] = $this->analysisCollector->getCategoryDifficultyByDate($year,
            $month);
        $this->analysisData['num_hours'] = $this->analysisCollector->getNumHoursByDate($year, $month);
        $this->analysisData['num_days'] = $this->analysisCollector->getFullWorkingDays($year, $month);
        $this->analysisData['num_lap'] = $this->analysisCollector->getNumTasksByDate($year, $month);
        $this->analysisData['num_hours_category'] = $this->analysisCollector->getNumHoursCategory($year, $month);

        return $this->analysisData;
    }

    public function charts($chart = null)
    {
        if ($this->charts === null) {
            $this->charts = [
                "hours" => new Chart(
                    array_map(function ($category) {
                        return $category->name;
                    }, $this->analysisData['num_hours_category']),
                    array_map(function ($category) {
                        return round($category->totalhours / $this->analysisData['num_hours'] * 100);
                    }, $this->analysisData['num_hours_category'])
                ),

                "categories" => new Chart(
                    array_map(function ($category) {
                        return $category->name;
                    }, $this->analysisData['category_difficulty']),
                    array_map(function ($category) {
                        return $category->difficulty;
                    }, $this->analysisData['category_difficulty'])
                ),
            ];
        }
        if ($chart === null) {
            return $this->charts;
        } else {
            return $this->charts[$chart];
        }

    }

    public function statistic($name)
    {
        return $this->statistics->$name();
    }

    /**
     * @return ProducingAnalysisChain[]
     */
    public function chains()
    {
        if($this->producingAnalysisChains === null) {
            $this->producingAnalysisChains = array_map(function ($chain) {
                return new ProducingAnalysisChain($chain);
            }, $this->chains);
        }
        return $this->producingAnalysisChains;
    }


}
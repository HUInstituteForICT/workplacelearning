<?php

declare(strict_types=1);

namespace App\Analysis\Producing;

use App\Chart;

/**
 * Class ProducingAnalysis used for getting analysis info of a producing user's activities.
 */
class ProducingAnalysis
{
    /**
     * @var ProducingAnalysisCollector class for fetching analysis data
     */
    public $analysisCollector;
    public $analysisData = [];

    // Holds Chart objects lazily
    private $charts;

    private $statistics;

    private $chains = [];
    private $producingAnalysisChains;

    public function __construct(ProducingAnalysisCollector $analysisCollector)
    {
        $this->analysisCollector = $analysisCollector;
        $this->statistics = new Statistics($this->analysisData);
    }

    /*
     * Build the data that is necessary for the analysis
     * Is based on earlier created functions, it merely wraps around those and provides access to the data
     */
    public function buildData($year, $month): array
    {
        $this->analysisData['avg_difficulty'] = $this->analysisCollector->getAverageDifficultyByDate($year, $month);
        $this->analysisData['num_total_lap'] = $this->analysisCollector->getNumTotalTasksByDate($year, $month);
        $this->analysisData['num_easy_lap'] = $this->analysisCollector->getNumEasyTasksByDate($year, $month);
        $this->analysisData['num_average_lap'] = $this->analysisCollector->getNumAverageTasksByDate($year, $month);
        $this->analysisData['num_difficult_lap'] = $this->analysisCollector->getNumDifficultTasksByDate($year, $month);
        $this->analysisData['hours_easy_lap'] = $this->analysisCollector->getHoursEasyTasksByDate($year, $month);
        $this->analysisData['hours_average_lap'] = $this->analysisCollector->getHoursAverageTasksByDate($year, $month);
        $this->analysisData['hours_difficult_lap'] = $this->analysisCollector->getHoursDifficultTasksByDate(
            $year,
            $month
        );
        $this->analysisData['most_occuring_category'] = $this->analysisCollector->getMostOccuringCategoryByDate(
            $year,
            $month
        );
        $this->analysisData['num_hours_alone'] = $this->analysisCollector->getNumHoursAlone($year, $month);
        $this->analysisData['category_difficulty'] = $this->analysisCollector->getCategoryDifficultyByDate(
            $year,
            $month
        )->toArray();

        $this->analysisData['person_difficulty'] = $this->analysisCollector->getResourcePersonDifficultyByDate(
            $year,
            $month
        )->first();

        if ($this->analysisData) {
            $this->analysisData['num_hours'] = $this->analysisCollector->getNumHoursByDate($year, $month);
        }
        $this->analysisData['num_days'] = $this->analysisCollector->getFullWorkingDays();
        $this->analysisData['num_lap'] = $this->analysisCollector->getNumTasksByDate($year, $month);
        $this->analysisData['num_hours_category'] = $this->analysisCollector->getNumHoursCategory($year, $month)->toArray();

        $this->statistics = new Statistics($this->analysisData);

        return $this->analysisData;
    }

    /**
     * @param null $chart a specific requested chart if passed, if none it returns all charts
     *
     * @return Chart[]|Chart returns the requested Chart(s)
     */
    public function charts($chart = null)
    {
        if ($this->charts === null) {
            $this->charts = [
                'hours' => new Chart(
                    array_map(function ($category) {
                        return $category->name;
                    }, $this->analysisData['num_hours_category']),
                    array_map(function ($category) {
                        return round($category->totalhours / $this->analysisData['num_hours'] * 100);
                    }, $this->analysisData['num_hours_category'])
                ),

                'categories' => new Chart(
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
        }

        return $this->charts[$chart];
    }

    /**
     * Returns the value of the requested statistic.
     *
     * @param $name string name of the method on the Statistic class
     *
     * @return mixed the statistic
     *
     * @throws \Exception if the statistic method is not found
     */
    public function statistic($name)
    {
        if (method_exists($this->statistics, $name)) {
            try {
                return $this->statistics->$name();
            } catch (\Exception $exception) {
                \Log::error('Error with statistic', [$exception]);

                return 0;
            }
        }
        throw new \Exception('Method not found on '.Statistics::class);
    }

    /**
     * Returns all activity chains of the user wrapped in ActivityChain objects.
     *
     * @return ActivityChain[]
     */
    public function chains()
    {
        if ($this->producingAnalysisChains === null) {
            $this->producingAnalysisChains = array_map(function ($chain) {
                return new ActivityChain($chain);
            }, $this->chains);
        }

        return $this->producingAnalysisChains;
    }
}

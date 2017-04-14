<?php


namespace App;


class ProducingAnalysisStatistics
{
    private $analysisData;

    public function __construct(array $analysisData)
    {
        $this->analysisData = $analysisData;
    }

    public function averageDifficulty() {
        return round($this->analysisData['avg_difficulty'],1);
    }

    public function percentageDifficultTasks() {
        return round((($this->analysisData['num_difficult_lap']/$this->analysisData['num_lap'])*100),1);
    }

    public function percentageDifficultHours() {
        return round((($this->analysisData['hours_difficult_lap']/$this->analysisData['num_hours'])*100),1);
    }

    public function percentageAloneHours() {
        return round(($this->analysisData['num_hours_alone']/$this->analysisData['num_hours'])*100,1);
    }
}
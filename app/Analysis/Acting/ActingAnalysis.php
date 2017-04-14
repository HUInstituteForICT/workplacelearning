<?php

namespace App\Analysis\Acting;

use App\Chart;

class ActingAnalysis
{
    public $analysisCollector;

    private $statistic;

    private $charts;

    public function __construct(ActingAnalysisCollector $analysisCollector)
    {
        $this->analysisCollector = $analysisCollector;
        $this->statistic = new Statistics($analysisCollector);
    }

    public function createCharts() {
        $this->charts = [
            "timeslot" => new Chart(
                $this->analysisCollector->getTimeslots()->map(function($timeslot){return $timeslot->timeslot_text;}),
                $this->analysisCollector->getTimeslots()->map(function($timeslot){return $this->statistic('percentageActivitiesInTimeslot', $timeslot);})
            )
        ];
    }

    public function charts($name) {
        if($this->charts === null) {
            $this->createCharts();
        }

        if($name === null) {
            return $this->charts;
        }
        return $this->charts[$name];
    }


    public function statistic($name, ...$args) {
        if(!$args === null) {
            return $this->statistic->$name();
        }
        return $this->statistic->$name(...$args);
    }
}
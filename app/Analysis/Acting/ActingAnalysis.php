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
            ),
            "learninggoal" => new Chart(
                $this->analysisCollector->getLearningGoals()->map(function($learningGoal){return $learningGoal->learninggoal_label;}),
                $this->analysisCollector->getLearningGoals()->map(function($learningGoal){return $this->statistic('percentageActivityForLearningGoal', $learningGoal);})
            ),
            "competence" => new Chart(
                $this->analysisCollector->getCompetencies()->map(function($competence){return $competence->competence_label;}),
                $this->analysisCollector->getCompetencies()->map(function($competence){return $this->statistic('percentageActivityForCompetence', $competence);})
            ),
            "person" => new Chart(
                $this->analysisCollector->getResourcePersons()->map(function($person){return $person->person_label;}),
                $this->analysisCollector->getResourcePersons()->map(function($person){return $this->statistic('percentageActivityWithResourcePerson', $person);})
            ),
            "material" => new Chart(
                $this->analysisCollector->getResourceMaterials()->map(function($material){return $material->rm_label;}),
                $this->analysisCollector->getResourceMaterials()->map(function($material){return $this->statistic('percentageActivityWithTheory', $material);})
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
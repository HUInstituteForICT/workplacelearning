<?php

namespace App\Analysis\Acting;

use App\Chart;
use App\Competence;
use App\ResourcePerson;
use App\Timeslot;

class ActingAnalysis
{
    /** @var ActingAnalysisCollector $analysisCollector for fetching analysis data */
    public $analysisCollector;

    private $statistics;

    private $charts;

    public function __construct(ActingAnalysisCollector $analysisCollector)
    {
        $this->analysisCollector = $analysisCollector;
        $this->statistics = new Statistics($analysisCollector);
    }

    /**
     * Creates the charts data for this analysis.
     */
    public function createCharts(): void
    {
        $this->charts = [
            'timeslot' => new Chart(
                $this->analysisCollector->getTimeslots()->map(function (Timeslot $timeslot) {return $timeslot->localizedLabel(); }),
                $this->analysisCollector->getTimeslots()->map(function (Timeslot $timeslot) {return $this->statistic('percentageActivitiesInTimeslot', $timeslot); })
            ),
            'learninggoal' => new Chart(
                $this->analysisCollector->getLearningGoals()->map(function ($learningGoal) {return $learningGoal->learninggoal_label; }),
                $this->analysisCollector->getLearningGoals()->map(function ($learningGoal) {return $this->statistic('percentageActivityForLearningGoal', $learningGoal); })
            ),
            'competence' => new Chart(
                $this->analysisCollector->getCompetencies()->map(function (Competence $competence) {return $competence->localizedLabel(); }),
                $this->analysisCollector->getCompetencies()->map(function ($competence) {return $this->statistic('percentageActivityForCompetence', $competence); })
            ),
            'person' => new Chart(
                $this->analysisCollector->getResourcePersons()->map(function (ResourcePerson $person) {return $person->localizedLabel(); }),
                $this->analysisCollector->getResourcePersons()->map(function ($person) {return $this->statistic('percentageActivityWithResourcePerson', $person); })
            ),
            // Not used currently, might be reinstated later on?
            /*"material" => new Chart(
                $this->analysisCollector->getResourceMaterials()->map(function($material){return $material->rm_label;}),
                $this->analysisCollector->getResourceMaterials()->map(function($material){return $this->statistic('percentageActivityWithTheory', $material);})
            )*/
        ];
    }

    /**
     * @param null $name a specific requested chart if passed, if none it returns all charts
     *
     * @return Chart[]|Chart returns the requested Chart(s)
     */
    public function charts($name)
    {
        if ($this->charts === null) {
            $this->createCharts();
        }

        if ($name === null) {
            return $this->charts;
        }

        return $this->charts[$name];
    }

    /**
     * Returns the value of the requested statistic.
     *
     * @param $name string name of the method on the Statistic class
     * @param array $args Extra arguments passed that are necessary to calculate the statistic (i.e. LearningGoal, Timeslot)
     *
     * @return mixed the statistic
     *
     * @throws \Exception if the statistic method is not found
     */
    public function statistic($name, ...$args)
    {
        if (!method_exists($this->statistics, $name)) {
            throw new \Exception('Method not found on '.Statistics::class);
        }

        if (!$args === null) {
            return $this->statistics->$name();
        }

        return $this->statistics->$name(...$args);
    }
}

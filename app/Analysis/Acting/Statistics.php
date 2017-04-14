<?php


namespace App\Analysis\Acting;


use App\LearningActivityActing;
use App\LearningGoal;
use App\Timeslot;

class Statistics
{
    private $analysisCollector;

    public function __construct(ActingAnalysisCollector $analysisCollector)
    {
        $this->analysisCollector = $analysisCollector;
    }

    public function percentageActivitiesInTimeslot(Timeslot $timeslot)
    {
        $activities = $this->analysisCollector->getLearningActivities()->filter(/**
         * @param $activity LearningActivityActing
         */
            function (LearningActivityActing $activity) use ($timeslot) {
                return $activity->timeslot->timeslot_id == $timeslot->timeslot_id;
            });

        return round(($activities->count() / $this->analysisCollector->getLearningActivities()->count())*100, 1);
    }

    public function percentageActivityForLearningGoal(LearningGoal $learningGoal)
    {

    }
}
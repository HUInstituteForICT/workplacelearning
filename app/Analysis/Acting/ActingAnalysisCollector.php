<?php

namespace App\Analysis\Acting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ActingAnalysisCollector
{
    private $learningActivities;
    private $timeslots;
    private $learningGoals;

    public function __construct()
    {

    }

    /**
     * @return Collection
     */
    public function getLearningActivities() {
        if($this->learningActivities === null) {
            $this->learningActivities = Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()->get();
        }
        return $this->learningActivities;
    }

    /**
     * @return Collection
     */
    public function getTimeslots() {
        if($this->timeslots === null) {
            $this->timeslots = Auth::user()->getEducationProgram()->getTimeslots();
        }
        return $this->timeslots;
    }

    public function getLearningGoals() {
        if($this->learningGoals === null) {
            $this->learningGoals = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals();
        }
        return $this->learningGoals;
    }
}
<?php

namespace App\Services\Factories;

use App\Services\CurrentUserResolver;
use App\Timeslot;

class TimeslotFactory
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function createTimeslot(string $label): Timeslot
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $timeslot = new Timeslot();

        $timeslot->timeslot_text = $label;
        $timeslot->educationProgram()->associate($student->educationProgram);
        $timeslot->workplaceLearningPeriod()->associate($student->getCurrentWorkplaceLearningPeriod());

        $timeslot->save();

        return $timeslot;
    }
}

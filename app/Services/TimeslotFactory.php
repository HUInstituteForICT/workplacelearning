<?php

namespace App\Services;

use App\Student;
use App\Timeslot;

class TimeslotFactory
{
    /**
     * @var Student
     */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function createTimeslot(string $label): Timeslot
    {
        $timeslot = new Timeslot();

        $timeslot->timeslot_text = $label;
        $timeslot->educationProgram()->associate($this->student->educationProgram);
        $timeslot->workplaceLearningPeriod()->associate($this->student->getCurrentWorkplaceLearningPeriod());

        $timeslot->save();

        return $timeslot;
    }
}

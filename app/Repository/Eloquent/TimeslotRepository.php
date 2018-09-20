<?php

namespace App\Repository\Eloquent;

use App\Student;
use App\Timeslot;

class TimeslotRepository
{
    public function get(int $id): Timeslot
    {
        return (new Timeslot())::findOrFail($id);
    }

    public function save(Timeslot $timeslot): bool
    {
        return $timeslot->save();
    }

    /**
     * @return Timeslot[]
     */
    public function timeslotsAvailableForStudent(Student $student): array
    {
        return $student->currentCohort()->timeslots()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getTimeslots()
        )->all();
    }
}

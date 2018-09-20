<?php

namespace App\Repository\Eloquent;

use App\LearningActivityActing;
use App\Student;

class LearningActivityActingRepository
{
    public function get(int $id): LearningActivityActing
    {
        return (new LearningActivityActing())::findOrFail($id);
    }

    public function save(LearningActivityActing $learningActivityActing): bool
    {
        return $learningActivityActing->save();
    }

    /**
     * @return LearningActivityActing[]
     */
    public function getActivitiesForStudent(Student $student): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->orderBy('date', 'DESC')
            ->get()->all();
    }

    public function delete(LearningActivityActing $learningActivityActing): bool
    {
        try {
            $learningActivityActing->competence()->detach();

            return $learningActivityActing->delete();
        } catch (\Exception $e) {
            return false;
        }
    }
}

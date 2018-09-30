<?php

namespace App\Repository\Eloquent;

use App\LearningGoal;
use App\Student;

class LearningGoalRepository
{
    public function get(int $id): LearningGoal
    {
        return (new LearningGoal())::findOrFail($id);
    }

    public function save(LearningGoal $learningGoal): bool
    {
        return $learningGoal->save();
    }

    public function learningGoalsAvailableForStudent(Student $student): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->learningGoals()
            ->get()->all();
    }
}

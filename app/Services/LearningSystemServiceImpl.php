<?php


namespace App\Services;

use App\CompetenceDescription;
use App\EducationProgram;
use App\Interfaces\LearningSystemServiceInterface;
use App\LearningGoal;
use phpDocumentor\Reflection\Types\Collection;

class LearningSystemServiceImpl implements LearningSystemServiceInterface
{
    public function __construct(){}

    public function getLearningGoalsByWPLId(int $wplId): Collection
    {
        // TODO: Implement getLearningGoalsByWPLId() method.
    }

    public function getAllCompetences(): Collection
    {
        // TODO: Implement getAllCompetences() method.
    }

    public function getAllCompetenceDescriptions(): Collection
    {
        // TODO: Implement getAllCompetenceDescriptions() method.
    }

    public function getAllEducationPrograms(): Collection
    {
        // TODO: Implement getAllEducationPrograms() method.
    }

    public function getAllCategories(): Collection
    {
        // TODO: Implement getAllCategories() method.
    }

    public function getAllLearningGoals(): Collection
    {
        // TODO: Implement getAllLearningGoals() method.
    }

    public function getCompetencesByCohortId(int $cohortId): Collection
    {
        // TODO: Implement getCompetencesByCohortId() method.
    }

    public function getCompetencesByLAAId(int $laaId): Collection
    {
        // TODO: Implement getCompetencesByLAAId() method.
    }

    public function getCompetenceDescriptionByCohortId(int $cohortId): CompetenceDescription
    {
        // TODO: Implement getCompetenceDescriptionByCohortId() method.
    }

    public function getEducationProgramByCohortId(int $cohortId): EducationProgram
    {
        // TODO: Implement getEducationProgramByCohortId() method.
    }

    public function getEducationProgramByStudentId(int $studentId): EducationProgram
    {
        // TODO: Implement getEducationProgramByStudentId() method.
    }

    public function getEducationProgramByResourcePersonId(int $resourcePersonId): EducationProgram
    {
        // TODO: Implement getEducationProgramByResourcePersonId() method.
    }

    public function getEducationProgramByTimeslotId(int $timeslotId): EducationProgram
    {
        // TODO: Implement getEducationProgramByTimeslotId() method.
    }

    public function getCategoriesByWPLId(int $wplId): Collection
    {
        // TODO: Implement getCategoriesByWPLId() method.
    }

    public function getCategoriesByCohortId(int $cohortId): Collection
    {
        // TODO: Implement getCategoriesByCohortId() method.
    }

    public function getCategoryByLAPId(int $lapId): EducationProgram
    {
        // TODO: Implement getCategoryByLAPId() method.
    }

    public function getLearningGoalByLAAId(int $laaId): LearningGoal
    {
        // TODO: Implement getLearningGoalByLAAId() method.
    }

    public function updateLearningGoals(array $learningGoals): bool
    {
        // TODO: Implement updateLearningGoals() method.
    }
}
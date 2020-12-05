<?php


namespace App\Interfaces;

use App\Cohort;
use App\CompetenceDescription;
use App\EducationProgram;
use App\LearningGoal;
use Illuminate\Database\Eloquent\Collection;

interface LearningSystemServiceInterface
{
    public function getAllCompetences(): Collection;
    public function getAllCompetenceDescriptions(): Collection;
    public function getAllEducationPrograms(): Collection;
    public function getAllCategories(): array;
    public function getAllLearningGoals(): Collection;

    //Competence domain
    public function getCompetencesByCohortId(int $cohortId): Collection;
    public function getCompetencesByLAAId(int $laaId): Collection;

    //CompetenceDescription domain
    public function getCompetenceDescriptionByCohortId(int $cohortId): CompetenceDescription;

    //EducationProgram domain
    public function getEducationProgramByCohortId(int $cohortId): EducationProgram;
    public function getEducationProgramByStudentId(int $studentId): EducationProgram;
    public function getEducationProgramByResourcePersonId(int $resourcePersonId): EducationProgram;
    public function getEducationProgramByTimeslotId(int $timeslotId): EducationProgram;
    public function createEducationProgram(array $data): EducationProgram;
    public function createEducationProgramEntity($type, $value, Cohort $cohort): EducationProgram;
    public function deleteEducationProgramEntity($entityId, $type): bool;
    public function updateEducationProgramEntity($entityId, array $data): EducationProgram;
    public function updateEducationProgram(EducationProgram $program, array $data): bool;
    public function handleUploadedCompetenceDescription(Cohort $cohort, $fileData): CompetenceDescription;

    //Category domain
    public function getCategoriesByWPLId(int $wplId): Collection;
    public function getCategoriesByCohortId(int $cohortId): Collection;
    public function getCategoryByLAPId(int $lapId): EducationProgram;

    //LearningGoal domain
    public function getLearningGoalsByWPLId(int $wplId): Collection;
    public function getLearningGoalByLAAId(int $laaId): LearningGoal;
    public function updateLearningGoals(array $learningGoals): bool;











}
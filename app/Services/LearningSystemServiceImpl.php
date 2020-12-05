<?php


namespace App\Services;

use App\Cohort;
use App\CompetenceDescription;
use App\EducationProgram;
use App\EducationProgramsService;
use App\Interfaces\LearningSystemServiceInterface;
use App\LearningGoal;
use App\Repository\Eloquent\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class LearningSystemServiceImpl implements LearningSystemServiceInterface
{
    /**
     * @var LearningGoalUpdater
     */
    private $learningGoalUpdater;

    /**
     * @var EducationProgramsService
     */
    private $educationProgramsService;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(LearningGoalUpdater $learningGoalUpdater, EducationProgramsService $educationProgramsService, CategoryRepository $categoryRepository){
        $this->learningGoalUpdater = $learningGoalUpdater;
        $this->educationProgramsService = $educationProgramsService;
        $this->categoryRepository = $categoryRepository;
    }

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

    public function getAllCategories(): array
    {
        return $this->categoryRepository->all();
    }

    public function getAllLearningGoals(): Collection
    {
        // TODO: Implement getAllLearningGoals() method.
    }

    //Competence
    public function getCompetencesByCohortId(int $cohortId): Collection
    {
        // TODO: Implement getCompetencesByCohortId() method.
    }

    public function getCompetencesByLAAId(int $laaId): Collection
    {
        // TODO: Implement getCompetencesByLAAId() method.
    }

    //CompetenceDescription
    public function getCompetenceDescriptionByCohortId(int $cohortId): CompetenceDescription
    {
        // TODO: Implement getCompetenceDescriptionByCohortId() method.
    }

    //EducationProgram
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

    public function createEducationProgram(array $data): EducationProgram
    {
        try {
            return $this->educationProgramsService->createEducationProgram($data);
        } catch (\Exception $e) {
        }
    }

    public function createEducationProgramEntity($type, $value, Cohort $cohort): EducationProgram {
        return $this->educationProgramsService->createEntity($type, $value, $cohort);
    }

    public function deleteEducationProgramEntity($entityId, $type): bool {
        return $this->educationProgramsService->deleteEntity($entityId, $type);
    }

    public function updateEducationProgramEntity($entityId, array $data): EducationProgram {
        return $this->educationProgramsService->updateEntity($entityId, $data);
    }

    public function updateEducationProgram(EducationProgram $program, array $data): bool {
        return $this->educationProgramsService->updateProgram($program, $data);
    }

    public function handleUploadedCompetenceDescription(Cohort $cohort, $fileData): CompetenceDescription {
        return $this->educationProgramsService->handleUploadedCompetenceDescription($cohort, $fileData);
    }

    //Category
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
        return $this->learningGoalUpdater->updateLearningGoals($learningGoals);
    }
}
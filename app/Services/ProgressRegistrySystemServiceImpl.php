<?php

namespace App\Services;

use App\Folder;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\FolderRepository;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\TimeslotRepository;
use App\Repository\Eloquent\TipRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use App\SavedLearningItem;
use App\Student;
use App\WorkplaceLearningPeriod;
use phpDocumentor\Reflection\Types\Collection;

class ProgressRegistrySystemServiceImpl implements ProgressRegistrySystemServiceInterface
{
    /**
     * @var LearningSystemServiceImpl
     */
    private $learningSystemService;
    /**
     * @var StudentSystemServiceImpl
     */
    private $studentSystemService;
    /**
     * @var FolderSystemServiceImpl
     */
    private $folderSystemService;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    /**
     * @var SavedLearningItemRepository
     */
    private $savedLearningItemRepository;

    /**
     * @var TipRepository
     */
    private $tipRepository;

    /**
     * @var LearningActivityProducingRepository
     */
    private $learningActivityProducingRepository;

    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        LearningSystemServiceImpl $learningSystemService,
        StudentSystemServiceImpl $studentSystemService,
        FolderSystemServiceImpl $folderSystemService,
        CurrentUserResolver $currentUserResolver,
        SavedLearningItemRepository $savedLearningItemRepository,
        TipRepository $tipRepository,
        LearningActivityProducingRepository $learningActivityProducingRepository,
        LearningActivityActingRepository $learningActivityActingRepository,
        ResourcePersonRepository $resourcePersonRepository,
        CategoryRepository $categoryRepository
    ){
        $this->learningSystemService = $learningSystemService;
        $this->studentSystemService = $studentSystemService;
        $this->folderSystemService = $folderSystemService;
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllWorkPlaceLearningPeriods(): Collection
    {
        // TODO: Implement getAllWorkPlaceLearningPeriods() method.
    }

    public function getAllLearningActivityActing(): Collection
    {
        // TODO: Implement getAllLearningActivityActing() method.
    }

    public function getAllWorkPlaces(): Collection
    {
        // TODO: Implement getAllWorkPlaces() method.
    }

    public function getAllTimeslots(): Collection
    {
        // TODO: Implement getAllTimeslots() method.
    }

    public function getAllSavedLearningItems(): Collection
    {
        // TODO: Implement getAllSavedLearningItems() method.
    }

    public function findFolderById(int $folderId): ?Folder{
        //TODO implement. needs reference to folderService which does not yet exist.
    }

    public function getWorkPlaceLearningPeriodsByStudentId(int $studentId): Collection
    {
        // TODO: Implement getWorkPlaceLearningPeriodsByStudentId() method.
    }

    public function getWorkPlaceLearningPeriodsByCohortId(int $cohortId): Collection
    {
        // TODO: Implement getWorkPlaceLearningPeriodsByCohortId() method.
    }

    public function getWorkPlaceLearningPeriodsByWorkplaceId(int $workplaceId): Collection
    {
        // TODO: Implement getWorkPlaceLearningPeriodsByWorkplaceId() method.
    }

    public function getWorkPlaceLearningPeriodByCategoryId(int $categoryId): WorkplaceLearningPeriod
    {
        // TODO: Implement getWorkPlaceLearningPeriodByCategoryId() method.
    }

    public function getWorkPlaceLearningPeriodByLearningGoalId(int $learningGoalId): WorkplaceLearningPeriod
    {
        // TODO: Implement getWorkPlaceLearningPeriodByLearningGoalId() method.
    }

    public function getWorkPlaceLearningPeriodByResourcePersonId(int $resourcePersonId): WorkplaceLearningPeriod
    {
        // TODO: Implement getWorkPlaceLearningPeriodByResourcePersonId() method.
    }

    public function getLearningActivityActingsByCompetenceId(int $compId): Collection
    {
//        return $this->learningActivityActingRepository->
        // TODO: Implement getLearningActivityActingsByCompetenceId() method.
        // requires extra repository functionality on join table. Discuss with Remco before further implementation.
    }

    // TODO: Test methods.
    public function getLearningActivityActingsByLearningGoalId(int $sliId): Collection
    {
        return $this->learningActivityActingRepository->getByLearningGoalId($sliId);
    }

    // TODO: Test methods.
    public function getLearningActivityActingByResourcePersonId(int $resourcePersonId): LearningActivityActing
    {
        return $this->learningActivityActingRepository->getByResourcePersonId($resourcePersonId);
    }

    public function getWorkPlacesByStudentId(int $studentId): Collection
    {
        // TODO: Implement getWorkPlacesByStudentId() method.
    }

    public function getTimeslotsByEPId(int $epId): Collection
    {
        // TODO: Implement getTimeslotsByEPId() method.
    }

    public function getTimeslotsByCohortId(int $cohortId): Collection
    {
        // TODO: Implement getTimeslotsByCohortId() method.
    }

    public function getSavedLearningItemByFolderId(int $folderId): SavedLearningItem
    {
        // TODO: Implement getSavedLearningItemByFolderId() method.
    }

    public function getSavedLearningItemByStudentId(int $studentId): SavedLearningItem
    {
        // TODO: Implement getSavedLearningItemByStudentId() method.
    }

    public function getSavedLearningItemById(int $sliId) : SavedLearningItem{
        return $this->savedLearningItemRepository->findById($sliId);
    }

    public function getAllLearningActivityProducing(): Collection
    {
        // TODO: Implement getAllLearningActivityProducing() method.
    }

    public function getLearningActivityActingBySLIId(int $sliId): LearningActivityActing
    {
        // TODO: Implement getLearningActivityActingBySLIId() method.
    }

    public function getLearningActivityProducingByLAPId(int $lapId): LearningActivityProducing
    {
        // TODO: Implement getLearningActivityProducingByLAPId() method.
    }

    public function getLearningActivityProducingByResourcePersonId(int $resourcePersonId): LearningActivityProducing
    {
        // TODO: Implement getLearningActivityProducingByResourcePersonId() method.
    }

    public function getLearningActivityProducingByCategoryId(int $categoryId): LearningActivityProducing
    {
        // TODO: Implement getLearningActivityProducingByCategoryId() method.
    }
}
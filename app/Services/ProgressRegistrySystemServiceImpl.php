<?php

namespace App\Services;

use App\Folder;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\Workplace;
use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\Repository\Eloquent\CategoryRepository;
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
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @var WorkplaceRepository
     */
    private $workplaceRepository;

    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    /**
     * @var TimeslotRepository
     */
    private $timeslotRepository;

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
        CategoryRepository $categoryRepository,
        WorkplaceRepository $workplaceRepository,
        WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository,
        TimeslotRepository $timeslotRepository
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
        $this->workplaceRepository = $workplaceRepository;
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
        $this->timeslotRepository = $timeslotRepository;
    }

    public function getAllWorkPlaceLearningPeriods()
    {
        return $this->workplaceLearningPeriodRepository->all()->all();
    }

    public function getAllLearningActivityActing(): Collection
    {
        // TODO: Implement getAllLearningActivityActing() method.
    }

    public function getAllLearningActivityProducing(): Collection
    {
        // Placeholder
        return $this->learningActivityProducingRepository->get();
    }

    public function getAllWorkPlaces(): Collection
    {
        return $this->workplaceRepository->getAll();
    }

    public function getAllTimeslots(): Collection
    {
        // Placeholder
        return $this->timeslotRepository->get();
    }

    public function getAllSavedLearningItems(): Collection
    {
        // TODO: Implement getAllSavedLearningItems() method.
    }

    public function findFolderById(int $folderId): ?Folder{
        //TODO implement. needs reference to folderService which does not yet exist.
    }

    public function getWorkplaceLearningPeriodById(int $wplpId): WorkplaceLearningPeriod
    {
        return $this->workplaceLearningPeriodRepository->get($wplpId);
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

    public function updateWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod, array $data):bool
    {
        // TODO: Implement updateWorkplaceLearningPeriod() method.
    }

    public function deleteWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod): void
    {
        $this->workplaceLearningPeriodRepository->delete($workplaceLearningPeriod);
    }

    public function saveWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod): bool
    {
        return $this->workplaceLearningPeriodRepository->save($workplaceLearningPeriod);
    }

    public function getLearningActivityActingsByCompetenceId(int $compId): Collection
    {
        // Placeholder
        return $this->learningActivityActingRepository->get();
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

    public function getLearningActivityActingForStudent(Student $student): array{
        return $this->learningActivityActingRepository->getActivitiesForStudent($student);
    }

    public function deleteLearningActivityActing(LearningActivityActing $learningActivityActing) : bool
    {
        return $this->learningActivityActingRepository->delete($learningActivityActing);
    }

    public function getWorkPlacesByStudentId(int $studentId): Collection
    {
        // Placeholder
        return $this->workplaceRepository->get();
    }

    public function getTimeslotsByEPId(int $epId): Collection
    {
        // Placeholder
        return $this->timeslotRepository->get();
    }

    public function getTimeslotsByCohortId(int $cohortId): Collection
    {
        // Placeholder
        return $this->timeslotRepository->get();
    }

    public function getTimeslotsAvailableForStudent(Student $student): array {
        return $this->timeslotRepository->timeslotsAvailableForStudent($student);
    }

    public function getSavedLearningItemByFolderId(int $folderId): SavedLearningItem
    {
        // Placeholder
        return $this->savedLearningItemRepository->findById();
    }

    public function getSavedLearningItemByStudentId(int $studentId): Collection
    {
        return $this->savedLearningItemRepository->findByStudentnr($studentId);
    }

    public function getSavedLearningItemById(int $sliId) : SavedLearningItem{
        return $this->savedLearningItemRepository->findById($sliId);
    }

    public function getLearningActivityActingBySLIId(int $sliId): LearningActivityActing
    {
        // Placeholder
        return $this->savedLearningItemRepository->get();
    }

    public function getLearningActivityProducingByLAPId(int $lapId): LearningActivityProducing
    {
        return $this->learningActivityProducingRepository->get($lapId);
    }

    public function getLearningActivityProducingByResourcePersonId(int $resourcePersonId): LearningActivityProducing
    {
        // Placeholder
        return $this->learningActivityProducingRepository->get();
    }

    public function getLearningActivityProducingByCategoryId(int $categoryId): LearningActivityProducing
    {
        // Placeholder
        return $this->learningActivityProducingRepository->get();
    }

    public function updateWorkplace(Workplace $workplace, array $data): bool
    {
        return $this->workplaceRepository->update($workplace, $data);
    }

    public function saveWorkplace(Workplace $workplace): bool {
        return $this->workplaceRepository->save($workplace);
    }

    public function saveSavedLearningItem(SavedLearningItem $savedLearningItem): bool
    {
        return $this->savedLearningItemRepository->save($savedLearningItem);
    }

    public function getActivitiesProducingOfLastActiveDayForStudent(Student $student): array {
        return $this->learningActivityProducingRepository->getActivitiesOfLastActiveDayForStudent($student);
    }

    public function getActivitiesProducingForStudent(Student $student): array {
        return $this->learningActivityProducingRepository->getActivitiesForStudent($student);
    }

    public function getEarliestActivityProducingForStudent(Student $student): ?LearningActivityProducing {
        return $this->learningActivityProducingRepository->earliestActivityForStudent($student);
    }

    public function getLatestActivityProducingForStudent(Student $student): ?LearningActivityProducing {
        return $this->learningActivityProducingRepository->latestActivityForStudent($student);
    }

    public function deleteLearningActivityProducing(LearningActivityProducing $learningActivityProducing): bool {
        return $this->learningActivityProducingRepository->delete($learningActivityProducing);
    }

    public function saveLearningActivityProducing(LearningActivityProducing $learningActivityProducing): bool
    {
        return $this->learningActivityProducingRepository->save($learningActivityProducing);
    }

    public function savedLearningItemExists($category, $item_id, $student_id): bool {
        return $this->savedLearningItemRepository->itemExists($category, $item_id, $student_id);
    }

    public function deleteSavedLearningItem(SavedLearningItem $sli) {
        return $this->savedLearningItemRepository->delete($sli);
    }

    public function getAllTips(): Collection
    {
        return $this->tipRepository->all();
    }
}
<?php


namespace App\Interfaces;


use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\SavedLearningItem;
use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Eloquent\Collection;

interface ProgressRegistrySystemServiceInterface
{
    public function getAllWorkPlaceLearningPeriods();

    public function getAllLearningActivityActing(): Collection;
    public function getAllLearningActivityProducing(): Collection;
    public function getAllWorkPlaces(): Collection;
    public function getAllTimeslots(): Collection;
    public function getAllSavedLearningItems(): Collection;

    //TODO WorkplaceLearningPeriod domain
    public function getWorkPlaceLearningPeriodsByStudentId(int $studentId):Collection;
    public function getWorkPlaceLearningPeriodsByCohortId(int $cohortId):Collection;
    public function getWorkPlaceLearningPeriodsByWorkplaceId(int $workplaceId):Collection;
    public function getWorkPlaceLearningPeriodByCategoryId(int $categoryId):WorkplaceLearningPeriod;
    public function getWorkPlaceLearningPeriodByLearningGoalId(int $learningGoalId):WorkplaceLearningPeriod;
    public function getWorkPlaceLearningPeriodByResourcePersonId(int $resourcePersonId):WorkplaceLearningPeriod;
    public function getWorkplaceLearningPeriodById(int $wplpId):WorkplaceLearningPeriod;
    public function updateWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod, array $data):bool;
    public function deleteWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod):void;
    public function saveWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod):bool;

    //TODO LearningActivityActing domain
    public function getLearningActivityActingsByCompetenceId(int $compId):Collection;
    public function getLearningActivityActingsByLearningGoalId(int $sliId):Collection;
    public function getLearningActivityActingBySLIId(int $sliId):LearningActivityActing;
    public function getLearningActivityActingByResourcePersonId(int $resourcePersonId):LearningActivityActing;
    public function getLearningActivityActingForStudent(Student $student): array;
    public function deleteLearningActivityActing(LearningActivityActing $learningActivityActing): bool;

    //TODO LearningActivityProducing domain
    public function getLearningActivityProducingByLAPId(int $lapId):LearningActivityProducing;
    public function getLearningActivityProducingByResourcePersonId(int $resourcePersonId):LearningActivityProducing;
    public function getLearningActivityProducingByCategoryId(int $categoryId):LearningActivityProducing;
    public function getActivitiesProducingOfLastActiveDayForStudent(Student $student): array;
    public function getActivitiesProducingForStudent(Student $student): array;
    public function getEarliestActivityProducingForStudent(Student $student): ?LearningActivityProducing;
    public function getLatestActivityProducingForStudent(Student $student): ?LearningActivityProducing;
    public function deleteLearningActivityProducing(LearningActivityProducing $learningActivityProducing): bool;
    public function saveLearningActivityProducing(LearningActivityProducing $learningActivityProducing): bool;

    //TODO Workplace domain
    public function getWorkPlacesByStudentId(int $studentId):Collection;
    public function updateWorkplace(Workplace $workplace, array $data):bool;
    public function saveWorkplace(Workplace $workplace):bool;

    //TODO Timeslot domain
    public function getTimeslotsByEPId(int $epId):Collection;
    public function getTimeslotsByCohortId(int $cohortId):Collection;
    public function getTimeslotsAvailableForStudent(Student $student):array;

    //TODO SavedLearningItem domain
    public function getSavedLearningItemByFolderId(int $folderId): SavedLearningItem;
    public function getSavedLearningItemByStudentId(int $studentId): Collection;
    public function saveSavedLearningItem(SavedLearningItem $savedLearningItem): bool;
    public function savedLearningItemExists($category, $item_id, $student_id): bool;
    public function deleteSavedLearningItem(SavedLearningItem $sli);
    public function getSavedLearningItemById(int $sliId) : SavedLearningItem;

    //Tip domain.
    public function getAllTips(): Collection;
}
<?php


namespace App\Interfaces;


use App\LearningActivityActing;
use App\LearningActivityProducing;
use App\SavedLearningItem;
use App\WorkplaceLearningPeriod;
use phpDocumentor\Reflection\Types\Collection;

interface ProgressRegistrySystemServiceInterface
{
    public function getAllWorkPlaceLearningPeriods(): Collection;
    public function getAllLearningActivityActing(): Collection;
    public function getAllLearningActivityProducing(): Collection;
    public function getAllWorkPlaces(): Collection;
    public function getAllTimeslots(): Collection;
    public function getAllSavedLearningItems(): Collection;

    //WorkplaceLearningPeriod domain
    public function getWorkPlaceLearningPeriodsByStudentId(int $studentId):Collection;
    public function getWorkPlaceLearningPeriodsByCohortId(int $cohortId):Collection;
    public function getWorkPlaceLearningPeriodsByWorkplaceId(int $workplaceId):Collection;
    public function getWorkPlaceLearningPeriodByCategoryId(int $categoryId):WorkplaceLearningPeriod;
    public function getWorkPlaceLearningPeriodByLearningGoalId(int $learningGoalId):WorkplaceLearningPeriod;
    public function getWorkPlaceLearningPeriodByResourcePersonId(int $resourcePersonId):WorkplaceLearningPeriod;

    //LearningActivityActing domain
    public function getLearningActivityActingsByCompetenceId(int $compId):Collection;
    public function getLearningActivityActingsByLearningGoalId(int $sliId):Collection;
    public function getLearningActivityActingBySLIId(int $sliId):LearningActivityActing;
    public function getLearningActivityActingByResourcePersonId(int $resourcePersonId):LearningActivityActing;

    //LearningActivityProducing domain
    public function getLearningActivityProducingByLAPId(int $lapId):LearningActivityProducing;
    public function getLearningActivityProducingByResourcePersonId(int $resourcePersonId):LearningActivityProducing;
    public function getLearningActivityProducingByCategoryId(int $categoryId):LearningActivityProducing;

    //Workplace domain
    public function getWorkPlacesByStudentId(int $studentId):Collection;

    //Timeslot domain
    public function getTimeslotsByEPId(int $epId):Collection;
    public function getTimeslotsByCohortId(int $cohortId):Collection;

    //SavedLearningItem domain
    public function getSavedLearningItemByFolderId(int $folderId): SavedLearningItem;
    public function getSavedLearningItemByStudentId(int $studentId): SavedLearningItem;
}
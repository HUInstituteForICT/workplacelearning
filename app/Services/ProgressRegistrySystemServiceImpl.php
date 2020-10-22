<?php

namespace App\Services;

use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TimeslotRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use phpDocumentor\Reflection\Types\Collection;

class ProgressRegistrySystemServiceImpl implements ProgressRegistrySystemServiceInterface
{
    private $learningSystemService;
    private $studentSystemService;

    public function __construct(LearningSystemServiceImpl $learningSystemService, StudentSystemServiceImpl $studentSystemService) {
        $this->learningSystemService = $learningSystemService;
        $this->studentSystemService = $studentSystemService;
    }

    public function getAllLearningActivityActing(): Collection
    {
        LearningActivityActingRepository::all();

    }
    public function getAllSavedLearningItems(): Collection
    {
        SavedLearningItemRepository::all();
    }
    public function getAllTimeslots(): Collection
    {
        TimeslotRepository::all();
    }
    public function getAllWorkPlaceLearningPeriods(): Collection
    {
        WorkplaceLearningPeriodRepository::all();
    }
    public function getAllWorkPlaces(): Collection
    {
        WorkplaceRepository::all();
    }

}
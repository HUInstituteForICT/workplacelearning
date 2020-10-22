<?php

namespace App\Services;

use App\Interfaces\ProgressRegistrySystemServiceInterface;
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
use App\Student;
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
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->categoryRepository = $categoryRepository;
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
    public function getByStudentId(int $studentId): Student
    {
        StudentRepository::get($studentId);
    }


}
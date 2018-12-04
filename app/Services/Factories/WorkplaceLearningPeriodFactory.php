<?php

namespace App\Services\Factories;

use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Services\CurrentUserResolver;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;

class WorkplaceLearningPeriodFactory
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    public function __construct(CurrentUserResolver $currentUserResolver, WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
    }

    public function createWorkplaceLearningPeriod(array $data): WorkplaceLearningPeriod
    {
        $workplaceLearningPeriod = new WorkplaceLearningPeriod();
        $workplaceLearningPeriod->student()->associate($this->currentUserResolver->getCurrentUser());
        $workplaceLearningPeriod->workplace()->associate($data['workplace_id']);
        $workplaceLearningPeriod->cohort()->associate($data['cohort']);
        $workplaceLearningPeriod->startdate = Carbon::parse($data['startdate'])->format('Y-m-d');
        $workplaceLearningPeriod->enddate = Carbon::parse($data['enddate'])->format('Y-m-d');
        $workplaceLearningPeriod->nrofdays = $data['numdays'];
        $workplaceLearningPeriod->description = $data['internshipAssignment'];
        $workplaceLearningPeriod->hours_per_day = 7.5; // Although not used in acting, still set it as its not nullable in DB

        $this->workplaceLearningPeriodRepository->save($workplaceLearningPeriod);

        return $workplaceLearningPeriod;
    }
}

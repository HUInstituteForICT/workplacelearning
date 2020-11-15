<?php

declare(strict_types=1);

namespace App\Services\Updaters;

use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use App\WorkplaceLearningPeriod;

class WorkplaceLearningPeriodUpdater
{
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

//    /**
//     * @var WorkplaceRepository
//     */
//    private $workplaceRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    public function __construct(
        WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository,
//        WorkplaceRepository $workplaceRepository,
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService
    ) {
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
//        $this->workplaceRepository = $workplaceRepository;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
    }

    public function update(WorkplaceLearningPeriod $workplaceLearningPeriod, array $data): void
    {
        $workplace = $workplaceLearningPeriod->workplace;

        $workplace->wp_name = $data['workplace']['name'];
        $workplace->street = $data['workplace']['street'];
        $workplace->housenr = $data['workplace']['housenr'];
        $workplace->postalcode = $data['workplace']['postalcode'];
        $workplace->town = $data['workplace']['town'];
        $workplace->country = $data['workplace']['country'];

        $workplace->contact_name = $data['workplace']['person'];
        $workplace->contact_phone = $data['workplace']['phone'];
        $workplace->contact_email = $data['workplace']['email'];

//        $this->workplaceRepository->save($workplace);
        $this->progressRegistrySystemService->saveWorkplace($workplace);

        if (isset($data['workplaceLearningPeriod']['cohort_id'])) {
            $workplaceLearningPeriod->cohort()->associate($data['workplaceLearningPeriod']['cohort_id']);
        }

        $workplaceLearningPeriod->nrofdays = $data['workplaceLearningPeriod']['days'];
        $workplaceLearningPeriod->hours_per_day = $data['workplaceLearningPeriod']['hours_per_day'];
        $workplaceLearningPeriod->is_in_analytics = isset($data['workplaceLearningPeriod']['is_in_analytics']);

        $this->workplaceLearningPeriodRepository->save($workplaceLearningPeriod);
    }
}

<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LearningActivityProducingCreated;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Services\Factories\ChainFactory;

class AttachBusyActivityToNewChain
{
    /**
     * @var ChainFactory
     */
    private $chainFactory;
//    /**
//     * @var LearningActivityProducingRepository
//     */
//    private $learningActivityProducingRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    public function __construct(
        ChainFactory $chainFactory,
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService
//        LearningActivityProducingRepository $learningActivityProducingRepository
    ) {
        $this->chainFactory = $chainFactory;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
//        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
    }

    public function handle(LearningActivityProducingCreated $event): void
    {
        $activity = $event->getActivity();
        if ($activity->chain === null && $activity->status->isBusy()) {
            $chain = $this->chainFactory->createChain([
                'name'    => $this->generateName($activity->description),
                'wplp_id' => $activity->wplp_id,
            ]);

            $activity->chain()->associate($chain);
//            $this->learningActivityProducingRepository->save($activity);
            $this->progressRegistrySystemService->saveLearningActivityProducing($activity);
        }
    }

    private function generateName(string $description): string
    {
        return __('New chain').' - '.substr($description, 0, 15);
    }
}

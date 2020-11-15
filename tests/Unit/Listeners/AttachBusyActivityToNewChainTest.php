<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Chain;
use App\Events\LearningActivityProducingCreated;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\LearningActivityProducing;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Services\Factories\ChainFactory;
use App\Status;
use Tests\TestCase;

class AttachBusyActivityToNewChainTest extends TestCase
{
    public function testHandle(): void
    {
        $chain = $this->createMock(Chain::class);

        $chainFactory = $this->createMock(ChainFactory::class);
        $chainFactory->expects(self::once())->method('createChain')->willReturn($chain);

        $status = $this->createMock(Status::class);
        $status->expects(self::once())->method('isBusy')->willReturn(true);

        $activity = $this->createMock(LearningActivityProducing::class);
        $activity->expects(self::exactly(4))->method('__get')
            ->withConsecutive(['chain'], ['status'], ['description'], ['wplp_id'])
            ->willReturnOnConsecutiveCalls(null, $status, 'joejoe', 1);
        $activity->expects(self::once())->method('chain');

//        $learningActivityProducingRepository = $this->createMock(LearningActivityProducingRepository::class);
//        $learningActivityProducingRepository->expects(self::once())->method('save')->with($activity);

        $progressRegistrySystemService = $this->createMock(ProgressRegistrySystemServiceInterface::class);
        $progressRegistrySystemService->expects(self::once())->method('saveLearningActivityProducing')->with($activity);

        $event = $this->createMock(LearningActivityProducingCreated::class);
        $event->expects(self::once())->method('getActivity')->willReturn($activity);

        $attachBusyActivityToNewChain = new AttachBusyActivityToNewChain($chainFactory,
            $progressRegistrySystemService);

        $attachBusyActivityToNewChain->handle($event);
    }
}

<?php

namespace Test\Unit\Http\Controllers;

use App\Http\Controllers\ProducingActivityController;
use App\Http\Requests\LearningActivity\ProducingCreateRequest;
use App\Http\Requests\LearningActivity\ProducingUpdateRequest;
use App\LearningActivityProducing;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Services\AvailableProducingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\CustomProducingEntityHandler;
use App\Services\Factories\LAPFactory;
use App\Services\LAPUpdater;
use App\Services\LearningActivityProducingExportBuilder;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ProducingActivityControllerTest extends TestCase
{
    public function testShow(): void
    {
        $student = $this->createMock(Student::class);
        $student->expects(self::once())->method('getCurrentWorkplaceLearningPeriod');

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $repository = $this->createMock(LearningActivityProducingRepository::class);
        $repository->expects(self::once())->method('getActivitiesOfLastActiveDayForStudent')->willReturn([]);

        $exportBuilder = $this->createMock(LearningActivityProducingExportBuilder::class);
        $exportBuilder->expects(self::once())->method('getJson')->with([], null)->willReturn('some json string');
        $exportBuilder->expects(self::once())->method('getFieldLanguageMapping')->willReturn([]);

        $availableEntitiesFetcher = $this->createMock(AvailableProducingEntitiesFetcher::class);
        $availableEntitiesFetcher->expects(self::once())->method('getEntities')->willReturn([]);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $producingActivityController->show($availableEntitiesFetcher, $exportBuilder);
    }

    public function testEdit(): void
    {
        $currentUserResolver = $this->createMock(CurrentUserResolver::class);

        $repository = $this->createMock(LearningActivityProducingRepository::class);

        $availableEntitiesFetcher = $this->createMock(AvailableProducingEntitiesFetcher::class);
        $availableEntitiesFetcher->expects(self::once())->method('getEntities')->willReturn([]);

        $activity = $this->createMock(LearningActivityProducing::class);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $request = $this->createMock(Request::class);
        $producingActivityController->edit($activity, $availableEntitiesFetcher, $request);
    }

    public function testProgress(): void
    {
        $student = $this->createMock(Student::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $repository = $this->createMock(LearningActivityProducingRepository::class);
        $repository->expects(self::once())->method('getActivitiesForStudent')->with($student)->willReturn([]);
        $repository->expects(self::once())->method('earliestActivityForStudent')->with($student)->willReturn(null);
        $repository->expects(self::once())->method('latestActivityForStudent')->with($student)->willReturn(null);

        $exportBuilder = $this->createMock(LearningActivityProducingExportBuilder::class);
        $exportBuilder->expects(self::once())->method('getJson')->with([], null)->willReturn('some json string');
        $exportBuilder->expects(self::once())->method('getFieldLanguageMapping')->willReturn([]);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $producingActivityController->progress($exportBuilder);
    }

    public function testCreate(): void
    {
        $request = $this->createMock(ProducingCreateRequest::class);
        $request->expects(self::once())->method('all')->willReturn([]);

        $activity = $this->createMock(LearningActivityProducing::class);
        $activity->expects(self::once())->method('__get')->with('feedback')->willReturn(false);

        $customProducingEntityHandler = $this->createMock(CustomProducingEntityHandler::class);
        $customProducingEntityHandler->expects(self::once())->method('process')->willReturn([]);

        $lapFactory = $this->createMock(LAPFactory::class);
        $lapFactory->expects(self::once())->method('createLAP')->with([])->willReturn($activity);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);

        $repository = $this->createMock(LearningActivityProducingRepository::class);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $producingActivityController->create($request, $lapFactory, $customProducingEntityHandler);
    }

    public function testUpdate(): void
    {
        $request = $this->createMock(ProducingUpdateRequest::class);
        $request->expects(self::once())->method('all')->willReturn([]);

        $activity = $this->createMock(LearningActivityProducing::class);

        $lapUpdater = $this->createMock(LAPUpdater::class);
        $lapUpdater->expects(self::once())->method('update')->with($activity);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $repository = $this->createMock(LearningActivityProducingRepository::class);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $producingActivityController->update($request, $activity, $lapUpdater);
    }

    public function testDelete(): void
    {
        $activity = $this->createMock(LearningActivityProducing::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $repository = $this->createMock(LearningActivityProducingRepository::class);
        $repository->expects(self::once())->method('delete')->with($activity);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $producingActivityController = new ProducingActivityController($currentUserResolver, $repository, $session);
        $producingActivityController->delete($activity);
    }
}

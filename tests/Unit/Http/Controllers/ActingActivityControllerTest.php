<?php

namespace Test\Unit\Http\Controllers;

use App\Http\Controllers\ActingActivityController;
use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\AvailableActingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\EvidenceUploadHandler;
use App\Services\Factories\LAAFactory;
use App\Services\LAAUpdater;
use App\Services\LearningActivityActingExportBuilder;
use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class ActingActivityControllerTest extends TestCase
{
    public function testShow(): void
    {
        $student = $this->createMock(Student::class);
        $student->expects(self::once())->method('getCurrentWorkplaceLearningPeriod')->willReturn(new WorkplaceLearningPeriod());

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $redirector = $this->createMock(Redirector::class);

        $learningActivityActingRepository = $this->createMock(LearningActivityActingRepository::class);
        $learningActivityActingRepository->expects(self::once())->method('getActivitiesForStudent')->willReturn([]);

        $availableActingEntitiesFetcher = $this->createMock(AvailableActingEntitiesFetcher::class);
        $availableActingEntitiesFetcher->expects(self::once())->method('getEntities')->willReturn([]);

        $exportBuilder = $this->createMock(LearningActivityActingExportBuilder::class);
        $exportBuilder->expects(self::once())->method('getJson')->with([], 8)->willReturn(json_encode([]));
        $exportBuilder->expects(self::once())->method('getFieldLanguageMapping')->willReturn([]);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController($redirector, $currentUserResolver,
            $learningActivityActingRepository, $session);
        $actingActivityController->show($availableActingEntitiesFetcher, $exportBuilder);
    }

    public function testEdit(): void
    {
        $availableActingEntitiesFetcher = $this->createMock(AvailableActingEntitiesFetcher::class);
        $availableActingEntitiesFetcher->expects(self::once())->method('getEntities')->willReturn([]);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController(
            $this->createMock(Redirector::class),
            $this->createMock(CurrentUserResolver::class),
            $this->createMock(LearningActivityActingRepository::class),
            $session
        );

        $request = $this->createMock(Request::class);

        $actingActivityController->edit($this->createMock(LearningActivityActing::class),
            $availableActingEntitiesFetcher, $request);
    }

    public function testProgress(): void
    {
        $exportBuilder = $this->createMock(LearningActivityActingExportBuilder::class);
        $exportBuilder->expects(self::once())->method('getJson')->with([], null)->willReturn(json_encode([]));
        $exportBuilder->expects(self::once())->method('getFieldLanguageMapping')->willReturn([]);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController(
            $this->createMock(Redirector::class),
            $this->createMock(CurrentUserResolver::class),
            $this->createMock(LearningActivityActingRepository::class),
            $session
        );

        $actingActivityController->progress($exportBuilder);
    }

    public function testCreate(): void
    {
        $redirectResponse = $this->createMock(RedirectResponse::class);
//        $redirectResponse->expects(self::once())->method('with')->withAnyParameters()->willReturnSelf();

        $redirector = $this->createMock(Redirector::class);
//        $redirector->expects(self::once())->method('route')->with('process-acting')->willReturn($redirectResponse);

        $request = $this->createMock(ActingCreateRequest::class);
        $request->expects(self::once())->method('all')->willReturn([]);
        $request->expects(self::once())->method('hasFile')->willReturn(true);

        $laa = new LearningActivityActing();

        $LAAFactory = $this->createMock(LAAFactory::class);
        $LAAFactory->expects(self::once())->method('createLAA')->withAnyParameters()->willReturn($laa);

        $evidenceUploadHandler = $this->createMock(EvidenceUploadHandler::class);
        $evidenceUploadHandler->expects(self::once())->method('process')->with($request, $laa);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController(
            $redirector,
            $this->createMock(CurrentUserResolver::class),
            $this->createMock(LearningActivityActingRepository::class),
            $session
        );

        $actingActivityController->create($request, $LAAFactory, $evidenceUploadHandler);
    }

    public function testUpdate(): void
    {
        $redirectResponse = $this->createMock(RedirectResponse::class);
//        $redirectResponse->expects(self::once())->method('with')->withAnyParameters()->willReturnSelf();

        $redirector = $this->createMock(Redirector::class);
//        $redirector->expects(self::once())->method('route')->with('process-acting')->willReturn($redirectResponse);

        $request = $this->createMock(ActingUpdateRequest::class);
        $request->expects(self::once())->method('all')->willReturn([]);
        $request->expects(self::once())->method('hasFile')->willReturn(true);

        $laa = new LearningActivityActing();

        $LAAUpdater = $this->createMock(LAAUpdater::class);
        $LAAUpdater->expects(self::once())->method('update')->with($laa, [])->willReturn(true);

        $evidenceUploadHandler = $this->createMock(EvidenceUploadHandler::class);
        $evidenceUploadHandler->expects(self::once())->method('process')->with($request, $laa);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController(
            $redirector,
            $this->createMock(CurrentUserResolver::class),
            $this->createMock(LearningActivityActingRepository::class),
            $session
        );

        $actingActivityController->update($request, $laa, $evidenceUploadHandler, $LAAUpdater);
    }

    public function testDelete()
    {
        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('process-acting')->willReturn($this->createMock(RedirectResponse::class));

        $laa = new LearningActivityActing();

        $learningActivityRepository = $this->createMock(LearningActivityActingRepository::class);
        $learningActivityRepository->expects(self::once())->method('delete')->with($laa);

        $session = $this->createMock(\Illuminate\Contracts\Session\Session::class);

        $actingActivityController = new ActingActivityController(
            $redirector,
            $this->createMock(CurrentUserResolver::class),
            $learningActivityRepository,
            $session
        );

        $actingActivityController->delete($laa);
    }
}

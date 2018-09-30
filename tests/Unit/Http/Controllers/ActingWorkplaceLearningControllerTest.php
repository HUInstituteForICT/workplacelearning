<?php

namespace Test\Unit\Http\Controllers;

use App\Http\Controllers\ActingWorkplaceLearningController;
use App\Http\Requests\Workplace\ActingWorkplaceCreateRequest;
use App\LearningGoal;
use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentUserResolver;
use App\Services\Factories\ActingWorkplaceFactory;
use App\Student;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Tests\TestCase;

class ActingWorkplaceLearningControllerTest extends TestCase
{
    public function testShow(): void
    {
        $student = $this->createMock(Student::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $cohortRepository = $this->createMock(CohortRepository::class);
        $cohortRepository->expects(self::once())->method('cohortsAvailableForStudent')->with($student)->willReturn([]);

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($currentUserResolver);
        $this->assertInstanceOf(View::class, $actingWorkplaceLearningController->show($cohortRepository));
    }

    public function testEdit(): void
    {
        $workplaceLearningPeriod = $this->createMock(WorkplaceLearningPeriod::class);
        $workplaceLearningPeriod->expects(self::exactly(2))->method('__get')
            ->withConsecutive(['workplace'], ['learningGoals'])
            ->willReturnOnConsecutiveCalls(
                [$this->createMock(Workplace::class)],
                [collect([$this->createMock(LearningGoal::class)])]
            );

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($this->createMock(CurrentUserResolver::class));
        $this->assertInstanceOf(View::class, $actingWorkplaceLearningController->edit($workplaceLearningPeriod));
    }

    public function testCreate(): void
    {
        $request = $this->createMock(ActingWorkplaceCreateRequest::class);
        $request->expects(self::once())->method('all')->willReturn([]);

        $actingWorkplaceFactory = $this->createMock(ActingWorkplaceFactory::class);
        $actingWorkplaceFactory->expects(self::once())->method('createEntities')->with([]);

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects(self::once())->method('with')->withAnyParameters()->willReturnSelf();

        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('profile')->willReturn($redirectResponse);

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($this->createMock(CurrentUserResolver::class));
        $actingWorkplaceLearningController->create($request, $actingWorkplaceFactory, $redirector);
    }
}

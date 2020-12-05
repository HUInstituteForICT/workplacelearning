<?php

declare(strict_types=1);

namespace Test\Unit\Http\Controllers;

use App\Http\Controllers\ActingWorkplaceLearningController;
use App\Http\Requests\Workplace\ActingLearningGoalsUpdateRequest;
use App\Http\Requests\Workplace\ActingWorkplaceCreateRequest;
use App\LearningGoal;
//use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentPeriodResolver;
use App\Services\CurrentUserResolver;
use App\Services\Factories\ActingWorkplaceFactory;
use App\Services\Factories\LearningGoalFactory;
//use App\Services\LearningGoalUpdater;
use App\Services\LearningSystemServiceImpl;
use App\Services\ProgressRegistrySystemServiceImpl;
use App\Services\StudentSystemServiceImpl;
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

        $studentSystemService = $this->createMock(StudentSystemServiceImpl::class);
        $studentSystemService->expects(self::once())->method('cohortsAvailableForStudent')->with($student)->willReturn([]);

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($currentUserResolver, $studentSystemService, $this->createMock(ProgressRegistrySystemServiceImpl::class), $this->createMock(LearningSystemServiceImpl::class));
        $this->assertInstanceOf(View::class, $actingWorkplaceLearningController->show());
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

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($this->createMock(CurrentUserResolver::class), $this->createMock(StudentSystemServiceImpl::class), $this->createMock(ProgressRegistrySystemServiceImpl::class), $this->createMock(LearningSystemServiceImpl::class));
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

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($this->createMock(CurrentUserResolver::class), $this->createMock(StudentSystemServiceImpl::class), $this->createMock(ProgressRegistrySystemServiceImpl::class), $this->createMock(LearningSystemServiceImpl::class));
        $actingWorkplaceLearningController->create($request, $actingWorkplaceFactory, $redirector);
    }

    public function testUpdateLearningGoals(): void
    {
        $request = $this->createMock(ActingLearningGoalsUpdateRequest::class);

        $request->expects(self::exactly(2))->method('has')
            ->withConsecutive(['learningGoal'], ['new_learninggoal_name'])
            ->willReturnOnConsecutiveCalls([true], [true]);

        $request->expects(self::exactly(4))->method('get')
            ->withConsecutive(['learningGoal'], ['new_learninggoal_name'], ['new_learninggoal_name'], ['new_learninggoal_description'])
            ->willReturnOnConsecutiveCalls([['empty array']], 'some label', 'some label', 'some description');

        $period = $this->createMock(WorkplaceLearningPeriod::class);
        $period->expects(self::exactly(2))->method('__get')->willReturn(1);

        $currentPeriodResolver = $this->createMock(CurrentPeriodResolver::class);
        $currentPeriodResolver->expects(self::exactly(2))->method('getPeriod')->willReturn($period);

        $learningSystemService = $this->createMock(LearningSystemServiceImpl::class);
        $learningSystemService->expects(self::once())->method('updateLearningGoals')->withAnyParameters();

        $learningGoalFactory = $this->createMock(LearningGoalFactory::class);
        $learningGoalFactory->expects(self::once())->method('createLearningGoal')->withAnyParameters();

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects(self::once())->method('with')->withAnyParameters()->willReturnSelf();

        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('period-acting-edit')->willReturn($redirectResponse);

        $actingWorkplaceLearningController = new ActingWorkplaceLearningController($this->createMock(CurrentUserResolver::class), $this->createMock(StudentSystemServiceImpl::class), $this->createMock(ProgressRegistrySystemServiceImpl::class), $learningSystemService);
        $actingWorkplaceLearningController->updateLearningGoals($request, $learningGoalFactory, $currentPeriodResolver, $redirector);
    }
}

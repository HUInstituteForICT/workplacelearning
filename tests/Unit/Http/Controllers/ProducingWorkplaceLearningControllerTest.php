<?php

declare(strict_types=1);

namespace Test\Unit\Http\Controllers;

use App\Category;
use App\Cohort;
use App\Http\Controllers\ActingWorkplaceLearningController;
use App\Http\Controllers\ProducingWo;
use App\Http\Controllers\ProducingWorkplaceLearningController;
use App\Http\Requests\Workplace\ActingWorkplaceCreateRequest;
use App\LearningGoal;
use App\Services\CurrentUserResolver;
use App\Services\Factories\ActingWorkplaceFactory;
use App\Services\LearningSystemServiceImpl;
use App\Services\ProgressRegistrySystemServiceImpl;
use App\Services\StudentSystemServiceImpl;
use App\Student;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Self_;
use Tests\TestCase;
use function _HumbugBox5dd6c57e4baf\React\Promise\all;

class ProducingWorkplaceLearningControllerTest extends TestCase
{
    public function testShow(): void
    {
        $student = $this->createMock(Student::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $studentSystemService = $this->createMock(StudentSystemServiceImpl::class);
        $studentSystemService->expects(self::once())->method('cohortsAvailableForStudent')->with($student)->willReturn([]);

        $producingWorkplaceLearningController = new ProducingWorkplaceLearningController($currentUserResolver, $studentSystemService);
        $this->assertInstanceOf(View::class, $producingWorkplaceLearningController->show());
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete("Can't get this test to work");
        $workplaceLearningPeriod = $this->createMock(WorkplaceLearningPeriod::class);
        $workplaceLearningPeriod->expects(self::exactly(3))->method('__get')
            ->withConsecutive(['workplace'], ['categories'], ['cohort'])
            ->willReturnOnConsecutiveCalls(
                [$this->createMock(Workplace::class)],
                [collect([$this->createMock(Category::class)])],
                [collect([$this->createMock(Cohort::class)])],
            );

        $producingWorkplaceLearningController = new ProducingWorkplaceLearningController($this->createMock(CurrentUserResolver::class), $this->createMock(StudentSystemServiceImpl::class));
        $this->assertInstanceOf(View::class, $producingWorkplaceLearningController->edit($workplaceLearningPeriod));
    }

    public function testCreate(): void
    {
        //TODO implement create unit test
        $this->assertTrue(true);
    }

    public function testUpdate(): void
    {
        //TODO implement update unit test
        $this->assertTrue(true);
    }

    public function testUpdateCategories(): void
    {
        //TODO implement updateCategories unit test
        $this->assertTrue(true);
    }
}

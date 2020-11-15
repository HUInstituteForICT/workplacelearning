<?php

declare(strict_types=1);

namespace Test\Unit\Http\Controllers;

use App\Cohort;
use App\Http\Controllers\ActingAnalysisController;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Services\CurrentPeriodResolver;
use App\Tips\Services\ApplicableTipFetcher;
use App\Tips\Services\TipPicker;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class ActingAnalysisControllerTest extends TestCase
{
    public function testShowChoiceScreen(): void
    {
        $period = $this->createMock(WorkplaceLearningPeriod::class);
        $period->expects(self::exactly(2))->method('hasLoggedHours')->willReturn(false, true);
        $period->expects(self::exactly(2))->method('__get')->withConsecutive(['startdate'],
            ['enddate'])->willReturnOnConsecutiveCalls(new \DateTime(),
            new \DateTime());

        $periodResolver = $this->createMock(CurrentPeriodResolver::class);
        $periodResolver->expects(self::exactly(2))->method('getPeriod')->willReturn($period);

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects(self::once())->method('withErrors')->withAnyParameters();

        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('home-acting')->willReturn($redirectResponse);

        $actingAnalysisController = new ActingAnalysisController($periodResolver, $redirector, $this->createMock(ProgressRegistrySystemServiceInterface::class));

        $actingAnalysisController->showChoiceScreen();
        $actingAnalysisController->showChoiceScreen();
    }

    public function testShowDetail(): void
    {
        $learningActivityActingRelation = $this->createMock(Collection::class);
        $learningActivityActingRelation->expects(self::exactly(2))->method('count')->willReturn(0, 1);

        $cohort = $this->createMock(Cohort::class);

        $period = $this->createMock(WorkplaceLearningPeriod::class);
        $period->expects(self::exactly(3))->method('__get')
            ->withConsecutive(['learningActivityActing'], ['learningActivityActing'], ['cohort'])
            ->willReturnOnConsecutiveCalls($learningActivityActingRelation, $learningActivityActingRelation,
                $cohort);

        $periodResolver = $this->createMock(CurrentPeriodResolver::class);
        $periodResolver->expects(self::exactly(2))->method('getPeriod')->willReturn($period);

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects(self::once())->method('withErrors')->withAnyParameters();

        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('home-acting')->willReturn($redirectResponse);

        $applicableTipFetcher = $this->createMock(ApplicableTipFetcher::class);
        $applicableTipFetcher->expects(self::once())->method('fetchForCohort')->with($cohort);

        $tipPicker = $this->createMock(TipPicker::class);
        $tipPicker->expects(self::once())->method('markTipsViewed');

        $actingAnalysisController = new ActingAnalysisController($periodResolver, $redirector, $this->createMock(ProgressRegistrySystemServiceInterface::class));

        $actingAnalysisController->showDetail('all', 'all', $applicableTipFetcher, $tipPicker);

        $actingAnalysisController->showDetail('all', 'all', $applicableTipFetcher, $tipPicker);
    }
}

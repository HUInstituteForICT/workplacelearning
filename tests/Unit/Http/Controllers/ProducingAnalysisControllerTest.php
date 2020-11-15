<?php

declare(strict_types=1);

namespace Test\Unit\Http\Controllers;

use App\Analysis\Producing\ProducingAnalysis;
use App\Analysis\Producing\ProducingAnalysisCollector;
use App\Cohort;
use App\Http\Controllers\ProducingAnalysisController;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Services\CurrentPeriodResolver;
use App\Tips\Services\ApplicableTipFetcher;
use App\Tips\Services\TipPicker;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class ProducingAnalysisControllerTest extends TestCase
{
    public function testShowChoiceScreen(): void
    {
        $period = $this->createMock(WorkplaceLearningPeriod::class);
        $period->expects(self::exactly(2))->method('hasLoggedHours')->willReturn(false, true);
        $period->expects(self::exactly(2))->method('__get')
            ->withConsecutive(['startdate'], ['enddate'])
            ->willReturnOnConsecutiveCalls(new \DateTime(), new \DateTime());

        $periodResolver = $this->createMock(CurrentPeriodResolver::class);
        $periodResolver->expects(self::exactly(2))->method('getPeriod')->willReturn($period);

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects(self::once())->method('withErrors')->withAnyParameters();

        $redirector = $this->createMock(Redirector::class);
        $redirector->expects(self::once())->method('route')->with('home-producing')->willReturn($redirectResponse);

        $analysisCollector = $this->createMock(ProducingAnalysisCollector::class);
        $analysisCollector->expects(self::once())->method('getFullWorkingDays')->with('all', 'all')->willReturn(1);

        $actingAnalysisController = new ProducingAnalysisController($periodResolver, $redirector, $this->createMock(ProgressRegistrySystemServiceInterface::class));

        $actingAnalysisController->showChoiceScreen($analysisCollector);
        $actingAnalysisController->showChoiceScreen($analysisCollector);
    }

    public function testShowDetail(): void
    {
        $cohort = $this->createMock(Cohort::class);

        $period = $this->createMock(WorkplaceLearningPeriod::class);
        $period->expects(self::once())->method('__get')
            ->with('cohort')
            ->willReturn($cohort);

        $periodResolver = $this->createMock(CurrentPeriodResolver::class);
        $periodResolver->expects(self::once())->method('getPeriod')->willReturn($period);

        $redirector = $this->createMock(Redirector::class);

        $applicableTipFetcher = $this->createMock(ApplicableTipFetcher::class);
        $applicableTipFetcher->expects(self::once())->method('fetchForCohort')->with($cohort);

        $tipPicker = $this->createMock(TipPicker::class);
        $tipPicker->expects(self::once())->method('markTipsViewed');

        $producingAnalysis = $this->createMock(ProducingAnalysis::class);
        $producingAnalysis->expects(self::once())->method('buildData')->with('all', 'all');

        $actingAnalysisController = new ProducingAnalysisController($periodResolver, $redirector, $this->createMock(ProgressRegistrySystemServiceInterface::class));

        $actingAnalysisController->showDetail('all', 'all', $applicableTipFetcher, $tipPicker, $producingAnalysis);
    }
}

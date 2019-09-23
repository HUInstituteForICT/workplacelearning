<?php

declare(strict_types=1);

namespace Unit\Tips;

use App\Services\CurrentPeriodResolver;
use App\Tips\PeriodMomentCalculator;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Tests\TestCase;

class PeriodMomentCalculatorTest extends TestCase
{
    public function testPeriodMomentCalculator(): void
    {
        $start = new Carbon(date('Y-m-d'));
        $start->subDay();

        $end = new Carbon(date('Y-m-d'));
        $end->addDay();

        $wplp = new WorkplaceLearningPeriod();
        $wplp->startdate = $start->format('Y-m-d');
        $wplp->enddate = $end->format('Y-m-d');

        $resolver = $this->createMock(CurrentPeriodResolver::class);
        $resolver->expects(self::once())->method('getPeriod')->willReturn($wplp);

        $periodMomentCalculator = new PeriodMomentCalculator($resolver);

        $this->assertEquals('50', $periodMomentCalculator->getMomentAsPercentage());
    }
}

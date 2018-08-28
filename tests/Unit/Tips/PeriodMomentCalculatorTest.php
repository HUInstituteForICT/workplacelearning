<?php


namespace Unit\Tips;


use App\Tips\PeriodMomentCalculator;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Tests\TestCase;

class PeriodMomentCalculatorTest extends TestCase
{
    public function testPeriodMomentCalculator()
    {
        $start = new Carbon(date('Y-m-d'));
        $start->subDay();

        $end = new Carbon(date('Y-m-d'));
        $end->addDay();

        $wplp = new WorkplaceLearningPeriod;
        $wplp->startdate = $start->format('Y-m-d');
        $wplp->enddate = $end->format('Y-m-d');

        $periodMomentCalculator = new PeriodMomentCalculator($wplp);

        $this->assertEquals('50', $periodMomentCalculator->getMomentAsPercentage());

    }
}
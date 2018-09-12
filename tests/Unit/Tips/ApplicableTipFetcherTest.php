<?php

namespace App\Tips;

use App\Cohort;
use App\Tips\Models\Tip;
use App\Tips\Services\ApplicableTipFetcher;
use App\Tips\Services\TipEvaluator;
use Illuminate\Support\Collection;

class ApplicableTipFetcherTest extends \PHPUnit\Framework\TestCase
{
    private function getTipMock()
    {
        /** @var Tip|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Tip::class);

        return $mock;
    }

    private function getCohortMock()
    {
        /** @var Cohort|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Cohort::class);
        $mock->expects($this->exactly(2))->method('load')->with('tips.coupledStatistics.statistic');
        $mock->expects($this->exactly(2))->method('__get')->with('tips')->willReturn(new Collection([$this->getTipMock()]));

        return $mock;
    }

    private function getTipEvaluatorMock()
    {
        /** @var TipEvaluator|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(TipEvaluator::class);
        $mock->expects($this->exactly(2))->method('evaluate')->withAnyParameters()->willReturn($this->getEvaluatedTipMock());

        return $mock;
    }

    private function getEvaluatedTipMock()
    {
        /** @var EvaluatedTip|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(EvaluatedTip::class);
        $mock->expects($this->exactly(2))->method('isPassing')->willReturn(true, false);

        return $mock;
    }

    public function testFetchForWorkplaceLearningPeriod(): void
    {
        $fetcher = new ApplicableTipFetcher($this->getTipEvaluatorMock());
        $cohort = $this->getCohortMock(); // One instance; contained tip returns different value on 2nd call
        $applicableTips = $fetcher->fetchForCohort($cohort);
        $this->assertCount(1, $applicableTips);

        $applicableTips = $fetcher->fetchForCohort($cohort);
        $this->assertCount(0, $applicableTips);
    }
}

<?php


namespace App\Tips;


use App\Cohort;
use App\Tips\DataCollectors\Collector;
use Illuminate\Support\Collection;

class ApplicableTipFetcherTest extends \PHPUnit\Framework\TestCase
{

    private function getTipMock()
    {
        /** @var Tip|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Tip::class);
        $mock->expects($this->exactly(2))->method('__get')->with('showInAnalysis')->willReturn(true, false);
        $mock->expects($this->once())->method('isApplicable')->withAnyParameters()->willReturn(true);

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

    private function getCollectorMock()
    {
        /** @var Collector|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Collector::class);

        return $mock;
    }


    public function testFetchForWorkplaceLearningPeriod()
    {
        $fetcher = new ApplicableTipFetcher();
        $cohort = $this->getCohortMock(); // One instance; contained tip returns different value on 2nd call
        $applicableTips = $fetcher->fetchForCohort($cohort, $this->getCollectorMock());
        $this->assertCount(1, $applicableTips);

        $applicableTips = $fetcher->fetchForCohort($cohort, $this->getCollectorMock());
        $this->assertCount(0, $applicableTips);
    }
}

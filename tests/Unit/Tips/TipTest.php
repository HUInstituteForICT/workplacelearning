<?php


use App\Student;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use App\Tips\TipService;


class TipTest extends \Tests\TestCase
{
    public function testTipApplicable() {
        /** @var \App\Tips\Statistics\CustomStatistic $statistic */
        $statistic = factory(\App\Tips\Statistics\CustomStatistic::class)->create();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();

        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.1,
            'multiplyBy100' => false,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);

        $collector = $this->createMock(\App\Tips\DataCollectors\Collector::class);
        $collector->method('getValueForVariable')->willReturn(0.3);

        $this->assertTrue($tip->isApplicable($collector));

        $tip->coupledStatistics->first()->threshold = 0.9;
        $this->assertFalse($tip->isApplicable($collector));
    }

    public function testTipText() {

        /** @var \App\Tips\Statistics\CustomStatistic $statistic */
        $statistic = factory(\App\Tips\Statistics\CustomStatistic::class)->create();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();

        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.1,
            'multiplyBy100' => true,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);

        $collector = $this->createMock(\App\Tips\DataCollectors\Collector::class);
        $collector->method('getValueForVariable')->willReturn(0.3);


        $tip->tipText = ":statistic-1 should be 60%";
        $tip->isApplicable($collector); // to trigger calculate

        $this->assertEquals("60% should be 60%", $tip->getTipText());

        $tip->tipText = ":statistic-1 should be 0.600";
        $tip->coupledStatistics->first()->multiplyBy100 = false;
        $tip->isApplicable($collector); // to trigger calculate
        $this->assertEquals("0.600% should be 0.600", $tip->getTipText());
    }

    public function testTipLike() {
        $tip = factory(Tip::class)->create();
        $student = factory(Student::class)->create();

        $tipService = new TipService();
        $result = $tipService->likeTip($tip, 1, $student);

        $this->assertTrue($result);

        $result = $tipService->likeTip($tip, 1, $student);

        $this->assertFalse($result);
    }

}

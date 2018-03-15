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

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectors\DataCollectorContainer::class);
        $dataCollectorContainer->method('getDataUnit')->willReturn(0.2);

        $this->assertTrue($tip->isApplicable($dataCollectorContainer));

        $tip->coupledStatistics->first()->threshold = 0.9;
        $this->assertFalse($tip->isApplicable($dataCollectorContainer));
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

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectors\DataCollectorContainer::class);
        $dataCollectorContainer->method('getDataUnit')->willReturn(10);


        $tip->tipText = ":value-1 should be 2,000%";
        $tip->isApplicable($dataCollectorContainer); // to trigger calculate

        $this->assertEquals("2,000% should be 2,000%", $tip->getTipText());

        $tip->tipText = ":value-1 should be 20";
        $tip->coupledStatistics->first()->multiplyBy100 = false;
        $tip->isApplicable($dataCollectorContainer); // to trigger calculate
        $this->assertEquals("20 should be 20", $tip->getTipText());
    }

    public function testTipLike() {
        $tip = factory(Tip::class)->create();
        $student = factory(Student::class)->create();

        $tipService = new TipService();
        $result = $tipService->likeTip($tip,$student);

        $this->assertTrue($result);

        $result = $tipService->likeTip($tip,$student);

        $this->assertFalse($result);
    }

}

<?php


use App\Student;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use App\Tips\TipService;


class TipTest extends \Tests\TestCase
{
    public function testTipApplicable() {
        /** @var \App\Tips\Statistic $statistic */
        $statistic = factory(\App\Tips\Statistic::class)->create();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();
        $tip->statistics()->attach($statistic, [
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.1,
            'multiplyBy100' => false,
        ]);

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectorContainer::class);
        $dataCollectorContainer->method('getDataUnit')->willReturn(0.2);

        $this->assertTrue($tip->isApplicable($dataCollectorContainer));

        $tip->statistics->first()->pivot->threshold = 0.9;
        $this->assertFalse($tip->isApplicable($dataCollectorContainer));
    }

    public function testTipText() {

        /** @var \App\Tips\Statistic $statistic */
        $statistic = factory(\App\Tips\Statistic::class)->create();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();
        $tip->statistics()->attach($statistic, [
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.1,
            'multiplyBy100' => true,
        ]);

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectorContainer::class);
        $dataCollectorContainer->method('getDataUnit')->willReturn(10);


        $tip->tipText = ":value-1 should be 2,000";
        $tip->isApplicable($dataCollectorContainer); // to trigger calculate

        $this->assertEquals("2,000 should be 2,000", $tip->getTipText());

        $tip->tipText = ":value-1 should be 20";
        $tip->statistics->first()->pivot->multiplyBy100 = false;
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

<?php


use App\Tips\Tip;


class TipTest extends PHPUnit\Framework\TestCase
{
    public function testTipApplicable() {

        $statistic = $this->createMock(\App\Tips\Statistic::class);
        $statistic->method('calculate')->willReturn(0.5);

        $tip = new Tip();
        $tip->statistic = $statistic;
        $tip->threshold = 0.4;

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectorContainer::class);

        $this->assertTrue($tip->isApplicable($dataCollectorContainer));

        $tip->threshold = 0.9;
        $this->assertFalse($tip->isApplicable($dataCollectorContainer));
    }

    public function testTipText() {
        $statistic = $this->createMock(\App\Tips\Statistic::class);
        $statistic->method('calculate')->willReturn(0.5);

        $dataCollectorContainer = $this->createMock(\App\Tips\DataCollectorContainer::class);


        $tip = new Tip();
        $tip->statistic = $statistic;
        $tip->tipText = ":percentage should be 50";
        $tip->multiplyBy100 = true;
        $tip->isApplicable($dataCollectorContainer); // to trigger calculate

        $this->assertEquals("50 should be 50", $tip->getTipText());

        $tip->tipText = ":percentage should be 0.5";
        $tip->multiplyBy100 = false;
        $tip->isApplicable($dataCollectorContainer); // to trigger calculate
        $this->assertEquals("0.5 should be 0.5", $tip->getTipText());

    }
}

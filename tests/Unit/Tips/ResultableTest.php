<?php


class ResultableTest extends \Tests\TestCase
{
    public function testStatisticResult()
    {
        $result = new \App\Tips\Statistics\StatisticResult(1, 'SomeEntity');
        $result->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN);
        $this->assertEquals('1', $result->getResultString());
        $this->assertTrue($result->hasPassed());
        $this->assertEquals('SomeEntity', $result->getName());

        $result->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_LESS_THAN);
        $this->assertFalse($result->hasPassed());


    }

    public function testStatisticCalculationResult()
    {
        $result = new \App\Tips\Statistics\StatisticCalculationResult(1, 'SomeEntity');
        $result->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN);
        $this->assertEquals('100%', $result->getResultString());
        $this->assertTrue($result->hasPassed());
        $this->assertEquals('SomeEntity', $result->getName());

        $result->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_LESS_THAN);
        $this->assertFalse($result->hasPassed());
    }

    public function testStatisticResultCollection()
    {
        $collection = new \App\Tips\Statistics\StatisticResultCollection();

        $calcResult = new \App\Tips\Statistics\StatisticCalculationResult(1, 'SomeEntity');
        $collection->addResult($calcResult);

        $collection->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN);

        $this->assertTrue($collection->hasPassed());

        $calcResult2 = new \App\Tips\Statistics\StatisticCalculationResult(0.1, 'SomeEntity2');
        $collection->addResult($calcResult2);

        $collection->doThresholdComparison(0.5, \App\Tips\Models\TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN);
        $this->assertFalse($collection->hasPassed());

        $this->assertEquals('SomeEntity, SomeEntity2', $collection->getName());
        $this->assertEquals('100%, 10%', $collection->getResultString());
    }
}
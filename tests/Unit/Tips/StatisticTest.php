<?php


use App\Tips\Statistics\CustomStatistic;


class StatisticTest extends \Tests\TestCase
{


    public function testCalculate() {
        $variableOne = $this->createMock(\App\Tips\Statistics\StatisticVariable::class);
        $variableOne->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $variableTwo = $this->createMock(\App\Tips\Statistics\StatisticVariable::class);
        $variableTwo->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $statistic = new CustomStatistic();
        $statistic->setCollector($this->createMock(\App\Tips\DataCollectors\Collector::class));
        $statistic->statisticVariableOne = $variableOne;
        $statistic->statisticVariableTwo = $variableTwo;


        $statistic->operator = CustomStatistic::OPERATOR_ADD;
        $this->assertEquals(6, $statistic->calculate()->firstResult()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_SUBTRACT;
        $this->assertEquals(0, $statistic->calculate()->firstResult()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_MULTIPLY;
        $this->assertEquals(9, $statistic->calculate()->firstResult()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_DIVIDE;
        $this->assertEquals(1, $statistic->calculate()->firstResult()->getResult());
    }
}

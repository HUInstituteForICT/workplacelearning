<?php


use App\Tips\CollectedDataStatisticVariable;
use App\Tips\DataCollector;
use App\Tips\Statistic;
use App\Tips\StatisticStatisticVariable;


class StatisticTest extends \Tests\TestCase
{


    public function testCalculate() {
        $variableOne = $this->createMock(CollectedDataStatisticVariable::class);
        $variableOne->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $variableTwo = $this->createMock(CollectedDataStatisticVariable::class);
        $variableTwo->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $statistic = new Statistic();
        $statistic->setDataCollector($this->createMock(DataCollector::class), null, null);
        $statistic->statisticVariableOne = $variableOne;
        $statistic->statisticVariableTwo = $variableTwo;


        $statistic->operator = Statistic::OPERATOR_ADD;
        $this->assertEquals(6, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_SUBTRACT;
        $this->assertEquals(0, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_MULTIPLY;
        $this->assertEquals(9, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_DIVIDE;
        $this->assertEquals(1, $statistic->calculate());
    }

    /**
     * Test that a nested statistic will also be calculated
     */
    public function testNestedStatisticCalculate() {
        // Create a nested statistic that is based on CollectedDataStatistics
        $nestedVariableOne = $this->createMock(CollectedDataStatisticVariable::class);
        $nestedVariableOne->expects($this->exactly(5))->method('getValue')->willReturn('3');

        $nestedVariableTwo = $this->createMock(CollectedDataStatisticVariable::class);
        $nestedVariableTwo->expects($this->exactly(5))->method('getValue')->willReturn('3');

        $nestedStatistic = new Statistic();
        $nestedStatistic->operator = Statistic::OPERATOR_ADD;
        $nestedStatistic->setDataCollector($this->createMock(DataCollector::class), null, null);
        $nestedStatistic->statisticVariableOne = $nestedVariableOne;
        $nestedStatistic->statisticVariableTwo = $nestedVariableTwo;

        // Make sure the nested statistic calculates correctly
        $this->assertEquals(6, $nestedStatistic->calculate());

        // Create the top level statistic
        $variableTwo = $this->createMock(CollectedDataStatisticVariable::class);
        $variableTwo->expects($this->exactly(4))->method('getValue')->willReturn('10');

        $variableOne = new StatisticStatisticVariable();
        $variableOne->nestedStatistic = $nestedStatistic;

        $statistic = new Statistic();
        $statistic->setDataCollector($this->createMock(DataCollector::class), null, null);
        $statistic->operator = Statistic::OPERATOR_MULTIPLY;
        $statistic->statisticVariableOne = $variableOne;
        $statistic->statisticVariableTwo = $variableTwo;

        // Assert all operators
        $statistic->operator = Statistic::OPERATOR_ADD;
        $this->assertEquals(16, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_SUBTRACT;
        $this->assertEquals(-4, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_MULTIPLY;
        $this->assertEquals(60, $statistic->calculate());

        $statistic->operator = Statistic::OPERATOR_DIVIDE;
        $this->assertEquals(0.6, $statistic->calculate());
    }
}

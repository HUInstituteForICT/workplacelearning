<?php


use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\Variables\CollectedDataStatisticVariable;
use App\Tips\Statistics\Variables\StatisticStatisticVariable;


class StatisticTest extends \Tests\TestCase
{


    public function testCalculate() {
        $variableOne = $this->createMock(CollectedDataStatisticVariable::class);
        $variableOne->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $variableTwo = $this->createMock(CollectedDataStatisticVariable::class);
        $variableTwo->expects($this->exactly(4))->method('getValue')->willReturn('3');

        $statistic = new CustomStatistic();
        $statistic->setDataCollector($this->createMock(DataCollectorContainer::class));
        $statistic->statisticVariableOne = $variableOne;
        $statistic->statisticVariableTwo = $variableTwo;


        $statistic->operator = CustomStatistic::OPERATOR_ADD;
        $this->assertEquals(6, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_SUBTRACT;
        $this->assertEquals(0, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_MULTIPLY;
        $this->assertEquals(9, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_DIVIDE;
        $this->assertEquals(1, $statistic->calculate()->getResult());
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

        $nestedStatistic = new CustomStatistic();
        $nestedStatistic->operator = CustomStatistic::OPERATOR_ADD;
        $nestedStatistic->setDataCollector($this->createMock(DataCollectorContainer::class));
        $nestedStatistic->statisticVariableOne = $nestedVariableOne;
        $nestedStatistic->statisticVariableTwo = $nestedVariableTwo;

        // Make sure the nested statistic calculates correctly
        $this->assertEquals(6, $nestedStatistic->calculate()->getResult());

        // Create the top level statistic
        $variableTwo = $this->createMock(CollectedDataStatisticVariable::class);
        $variableTwo->expects($this->exactly(4))->method('getValue')->willReturn('10');

        $variableOne = new StatisticStatisticVariable();
        $variableOne->nestedStatistic = $nestedStatistic;

        $statistic = new CustomStatistic();
        $statistic->setDataCollector($this->createMock(DataCollectorContainer::class));
        $statistic->operator = CustomStatistic::OPERATOR_MULTIPLY;
        $statistic->statisticVariableOne = $variableOne;
        $statistic->statisticVariableTwo = $variableTwo;

        // Assert all operators
        $statistic->operator = CustomStatistic::OPERATOR_ADD;
        $this->assertEquals(16, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_SUBTRACT;
        $this->assertEquals(-4, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_MULTIPLY;
        $this->assertEquals(60, $statistic->calculate()->getResult());

        $statistic->operator = CustomStatistic::OPERATOR_DIVIDE;
        $this->assertEquals(0.6, $statistic->calculate()->getResult());
    }
}

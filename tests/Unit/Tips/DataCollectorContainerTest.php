<?php


use App\Tips\DataCollectors\DataCollectorContainer;


class DataCollectorContainerTest extends PHPUnit\Framework\TestCase
{

    public function testGetPredefinedCollector()
    {
        $actingCollector = $this->createMock(\App\Tips\DataCollectors\ActingCollector::class);
        $predefinedCollector = $this->createMock(\App\Tips\DataCollectors\ActingPredefinedStatisticCollector::class);
        $actingCollector->predefinedStatisticCollector = $predefinedCollector;

        $dataCollectorContainer = new DataCollectorContainer($actingCollector);
        $this->assertEquals($predefinedCollector, $dataCollectorContainer->getPredefinedCollector());
    }

    /**
     * @throws Exception
     */
    public function testGetDataUnit()
    {
        $actingCollector = $this->createMock(\App\Tips\DataCollectors\ActingCollector::class);
        $dataUnit = $this->createMock(\App\Tips\DataUnit::class);
        $dataUnit->expects($this->any())->method('getMethod')->willReturn('testMethod');


        $dataCollectorContainer = new DataCollectorContainer($actingCollector);
        $this->expectException(\Exception::class);
        $dataCollectorContainer->getDataUnit($dataUnit);

        $actingCollector->expects($this->once())->method('testMethod')->willReturn('testMethodWorks');

        $this->assertEquals('testMethodWorks', $dataCollectorContainer->getDataUnit($dataUnit));


    }
}

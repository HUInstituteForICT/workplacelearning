<?php


use App\Chain;


class ChainTest extends \Tests\TestCase
{

    private function LapSaveMock():\App\LearningActivityProducing
    {
        $mock = $this->createMock(\App\LearningActivityProducing::class);
        $mock->expects(self::once())->method('setAttribute')->withAnyParameters();
        $mock->expects(self::once())->method('save')->willReturnSelf();
        return $mock;
    }

    public function testAddActivity()
    {
        $chain = new Chain();
        $chain->addActivity($this->LapSaveMock());
    }
}

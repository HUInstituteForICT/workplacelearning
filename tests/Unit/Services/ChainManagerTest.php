<?php


use App\ChainManager;


class ChainManagerTest extends \Tests\TestCase
{

    private function wplpMock(): \App\WorkplaceLearningPeriod
    {
        $mock = $this->createMock(\App\WorkplaceLearningPeriod::class);
        $mock->expects(self::once())->method('__get')->with('wplp_id')->willReturn(1);
        return $mock;
    }

    public function testCreateChain()
    {
        $manager = new ChainManager($this->wplpMock());
        $chain = $manager->createChain('new chain');

        $this->assertEquals('new chain', $chain->name);
        $this->assertEquals(1, $chain->wplp_id);

    }
}

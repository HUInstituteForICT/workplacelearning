<?php


use App\Chain;
use App\ChainManager;


class ChainManagerTest extends \Tests\TestCase
{

    private function LapSaveMock():\App\LearningActivityProducing
    {
        $relMock = $this->createMock(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
        $relMock->expects(self::once())->method('associate')->withAnyParameters();
        $relMock->expects(self::once())->method('dissociate')->withAnyParameters();

        $mock = $this->createMock(\App\LearningActivityProducing::class);
        $mock->expects(self::exactly(2))->method('chain')->willReturn($relMock);
        return $mock;
    }

    private function wplpMock():\App\WorkplaceLearningPeriod {
        $mock = $this->createMock(\App\WorkplaceLearningPeriod::class);
        $mock->expects(self::once())->method('__get')->with('wplp_id')->willReturn(1);

        return $mock;
    }

    private function chainMock():Chain
    {
        $mock = $this->createMock(Chain::class);
        $mock->expects(self::exactly(2))->method('__set')->with($this->logicalOr(
            $this->equalTo('name'), $this->equalTo('status')
        ));
        $mock->expects(self::once())->method('save');
        return $mock;
    }

    public function testCreateChain()
    {
        $chainManager = new ChainManager($this->wplpMock());

        $chain = $chainManager->createChain('new chain');

        $this->assertEquals('new chain', $chain->name);
        $this->assertEquals(1, $chain->wplp_id);
    }

    public function testUpdateChain()
    {
        $chainManager = new ChainManager(new \App\WorkplaceLearningPeriod());
        $chain = $this->chainMock();
        $chainManager->updateChain($chain, 'test name', Chain::STATUS_FINISHED);

    }

    public function testAttachDetachActivity()
    {
        $chainManager = new ChainManager(new \App\WorkplaceLearningPeriod());
        $chain = new Chain();

        $lapMock = $this->LapSaveMock();

        $chainManager->attachActivity($lapMock, $chain);
        $chainManager->detachActivity($lapMock);

        $this->assertNull($lapMock->chain_id);
    }
}

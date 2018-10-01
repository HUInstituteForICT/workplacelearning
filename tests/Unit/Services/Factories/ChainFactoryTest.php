<?php

namespace App\Services\Factories;

use App\Chain;
use App\Repository\Eloquent\ChainRepository;

class ChainFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateChain(): void
    {
        $chainRepository = $this->createMock(ChainRepository::class);
        $chainRepository->expects(self::once())->method('save')->withAnyParameters();

        $chainFactory = new ChainFactory($chainRepository);
        $chain = $chainFactory->createChain([
            'name'    => 'test',
            'wplp_id' => 1,
        ]);

        $this->assertInstanceOf(Chain::class, $chain);
    }
}

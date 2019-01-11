<?php

namespace App\Services\Factories;

use App\Chain;
use App\Repository\Eloquent\ChainRepository;

class ChainFactory
{
    /**
     * @var ChainRepository
     */
    private $chainRepository;

    public function __construct(ChainRepository $chainRepository)
    {
        $this->chainRepository = $chainRepository;
    }

    public function createChain(array $data): Chain
    {
        $chain = new Chain();
        $chain->name = $data['name'];
        $chain->status = Chain::STATUS_BUSY;
        $chain->wplp_id = $data['wplp_id'];

        $this->chainRepository->save($chain);

        return $chain;
    }
}

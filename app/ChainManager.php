<?php

namespace App;

use Illuminate\Auth\Access\AuthorizationException;

class ChainManager
{
    /**
     * @var WorkplaceLearningPeriod
     */
    private $workplaceLearningPeriod;

    public function __construct(WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        $this->workplaceLearningPeriod = $workplaceLearningPeriod;
    }

    public function createChain(string $name): Chain
    {
        $chain = new Chain();
        $chain->name = $name;
        $chain->status = Chain::STATUS_BUSY;
        $chain->wplp_id = $this->workplaceLearningPeriod->wplp_id;

        $chain->save();

        return $chain;
    }

    public function updateChain(Chain $chain, ?string $name, ?int $status)
    {
        $chain->name = $name ?? $chain->name;
        $chain->status = $status ?? $chain->status;

        $chain->save();
    }

    public function attachActivity(LearningActivityProducing $learningActivityProducing, Chain $chain)
    {
        $learningActivityProducing->chain()->associate($chain);
    }

    public function detachActivity(LearningActivityProducing $learningActivityProducing)
    {
        $learningActivityProducing->chain()->dissociate();
    }

    /**
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function deleteChain(Chain $chain): bool
    {
        if ($this->workplaceLearningPeriod->wplp_id !== $chain->wplp_id) {
            throw new AuthorizationException('No access to this chain');
        }

        return $chain->delete();
    }
}

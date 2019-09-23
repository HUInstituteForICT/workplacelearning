<?php

declare(strict_types=1);

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

    public function updateChain(Chain $chain, ?string $name, ?int $status): void
    {
        $chain->name = $name ?? $chain->name;
        $chain->status = $status ?? $chain->status;

        $chain->save();
    }

    public function attachActivity(LearningActivityProducing $learningActivityProducing, Chain $chain): void
    {
        $learningActivityProducing->chain()->associate($chain);
    }

    public function detachActivity(LearningActivityProducing $learningActivityProducing): void
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

<?php


namespace App;


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

    public function attachActivity(LearningActivityProducing $learningActivityProducing, Chain $chain)
    {
        $learningActivityProducing->chain()->associate($chain);
    }

    public function detachActivity(LearningActivityProducing $learningActivityProducing)
    {
        $learningActivityProducing->chain()->dissociate();
    }
}
<?php

namespace App\Events;

use App\LearningActivityProducing;

class LearningActivityProducingCreated
{
    /**
     * @var LearningActivityProducing
     */
    private $learningActivityProducing;

    public function __construct(LearningActivityProducing $learningActivityProducing)
    {
        $this->learningActivityProducing = $learningActivityProducing;
    }

    public function getActivity(): LearningActivityProducing
    {
        return $this->learningActivityProducing;
    }
}

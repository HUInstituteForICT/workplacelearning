<?php


namespace App\Tips;


class DataCollector
{
    /** @var CollectorInterface $collector */
    private $collector;

    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function getDataUnit($dataUnitName) {
        return $this->collector->get($dataUnitName);
    }
}
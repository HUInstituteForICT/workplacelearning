<?php


namespace App\Tips;


class DataCollector
{
    private $data = [];

    public function getDataUnit($unitName) {
        return $this->data[$unitName];
    }
}
<?php


namespace App\Analysis\Producing;


class ActivityChain
{
    private $chain;

    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    public function dateText()
    {
        $date = date('d-m', strtotime(reset($this->chain)->date));

        if (reset($this->chain) != end($this->chain)) {
            $date .= " t/m " . date('d-m', strtotime(end($this->chain)->date));
        }

        return $date;
    }

    public function descriptionText()
    {
        $description = reset($this->chain)->description;
        if (reset($this->chain) != end($this->chain)) {
            $description .= ' - ' . end($this->chain)->description;
        }

        return $description;
    }

    public function hoursText()
    {
        return array_sum(array_map(function ($chainEntry) {
            return $chainEntry->duration;
        }, $this->chain));
    }

    public function statusText() {
        return end($this->chain)->getStatus();
    }

    public function hasDetail() {
        if(reset($this->chain) != end($this->chain)) {
            return true;
        } else {
            return false;
        }
    }

    public function first() {
        return reset($this->chain);
    }

    public function last() {
        return end($this->chain);
    }

    public function count() {
        return count($this->chain);
    }

    public function raw()
    {
        return $this->chain;
    }
}
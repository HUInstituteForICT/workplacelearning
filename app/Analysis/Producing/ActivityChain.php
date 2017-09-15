<?php


namespace App\Analysis\Producing;

/**
 * Class ActivityChain wraps around activity chains and provides easily accessible methods for them
 * @package App\Analysis\Producing
 */
class ActivityChain
{
    private $chain;

    /**
     * ActivityChain constructor.
     * @param array $chain
     */
    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    /**
     * Get the date (range) of an activity chain
     *
     * @return string
     */
    public function dateText()
    {
        $date = date('d-m', strtotime(reset($this->chain)->date));

        if (reset($this->chain) != end($this->chain)) {
            $date .= " t/m " . date('d-m', strtotime(end($this->chain)->date));
        }

        return $date;
    }

    /**
     * Get the description text of an activity chain
     * @return string
     */
    public function descriptionText()
    {
        $description = reset($this->chain)->description;
        if (reset($this->chain) != end($this->chain)) {
            $description .= ' - ' . end($this->chain)->description;
        }

        return $description;
    }

    /**
     * Get the summed hours text of an activity chain
     * @return float|int
     */
    public function hoursText()
    {
        return array_sum(array_map(function ($chainEntry) {
            return $chainEntry->duration;
        }, $this->chain));
    }

    /**
     * Get the status text of an activity chain
     * @return mixed
     */
    public function statusText()
    {
        return end($this->chain)->getStatus();
    }

    /**
     * Check if the activity chain has more than one activity
     * @return bool
     */
    public function hasDetail()
    {
        return true;
        if (reset($this->chain) != end($this->chain)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the first activity of the chain
     * @return mixed
     */
    public function first()
    {
        return reset($this->chain);
    }

    /**
     * Get the last activity of the chain
     * @return mixed
     */
    public function last()
    {
        return end($this->chain);
    }

    /**
     * Get the amount of activities in the chain
     * @return int
     */
    public function count()
    {
        return count($this->chain);
    }

    /**
     * Get the activity chain in a raw array
     * Used when the wrapper is not enough
     * @return array
     */
    public function raw()
    {
        return $this->chain;
    }
}

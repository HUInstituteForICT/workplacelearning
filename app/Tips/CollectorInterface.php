<?php


namespace App\Tips;


use App\WorkplaceLearningPeriod;

interface CollectorInterface
{
    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod);

    /**
     * Get the value of the dataUnit by name
     *
     * @param $dataUnitName
     * @return float|int
     */
    public function get($dataUnitName);
}
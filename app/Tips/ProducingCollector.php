<?php


namespace App\Tips;


use App\WorkplaceLearningPeriod;

class ProducingCollector implements CollectorInterface
{
    /** @var string|int $year */
    private $year;
    /** @var string|int $month */
    private $month;
    /** @var WorkplaceLearningPeriod $learningPeriod */
    private $learningPeriod;

    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod)
    {
        $this->year = $year;
        $this->month = $month;
        $this->learningPeriod = $learningPeriod;
    }

    /**
     * Get the value of the dataUnit by name
     *
     * @param $dataUnitName
     * @return float|int
     */
    public function get($dataUnitName)
    {
        // TODO: Implement get() method.
    }
}
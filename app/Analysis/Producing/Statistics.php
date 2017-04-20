<?php


namespace App\Analysis\Producing;

/**
 * Class Statistics provides easy access to statistics of user's activities
 * @package App\Analysis\Producing
 */
class Statistics
{
    private $analysisData;

    public function __construct(array $analysisData)
    {
        $this->analysisData = $analysisData;
    }

    /**
     * @return float perceived difficulty of user's tasks
     */
    public function averageDifficulty() {
        return round($this->analysisData['avg_difficulty'],1);
    }

    /**
     * @return float percentage of tasks that the user found difficult
     */
    public function percentageDifficultTasks() {
        return round((($this->analysisData['num_difficult_lap']/$this->analysisData['num_lap'])*100),1);
    }

    /**
     * @return float percentage of hours the user found difficult
     */
    public function percentageDifficultHours() {
        return round((($this->analysisData['hours_difficult_lap']/$this->analysisData['num_hours'])*100),1);
    }

    /**
     * @return float percentage of hours the user spent working alone
     */
    public function percentageAloneHours() {
        return round(($this->analysisData['num_hours_alone']/$this->analysisData['num_hours'])*100,1);
    }
}
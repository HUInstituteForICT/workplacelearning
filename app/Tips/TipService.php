<?php


namespace App\Tips;


use App\Cohort;

class TipService
{

    /**
     * Create a new tip based on the data passed
     *
     * @param Tip $tip
     * @param array $data
     * @return Tip
     */
    public function setTipData(Tip $tip, array $data) {

        $tip->name = $data['name'];
        $tip->tipText = $data['tipText'];
        $tip->showInAnalysis = isset($data['showInAnalysis']);

        $tip->save();

        $this->coupleStatistics($tip, $data['statistics']);

        return $tip;
    }

    /**
     * Enable this tip for selected cohorts
     *
     * @param Tip $tip
     * @param array $data
     * @return Tip
     */
    public function enableCohorts(Tip $tip, array $data)
    {
        $tip->load('enabledCohorts');

        // Delete all current attached cohorts
        $tip->enabledCohorts()->detach();
        // Enable/attach all selected cohorts
        $tip->enabledCohorts()->attach($data['enabledCohorts']);

        return $tip;
    }

    /**
     * Couple the selected statistics to the Tip
     *
     * @param Tip $tip
     * @param array $statisticsData
     * @return void
     */
    public function coupleStatistics(Tip $tip, array $statisticsData) {
        foreach($statisticsData as $statisticId => $couplingData) {
            $statistic = (new Statistic)->findOrFail($statisticId);
            $tip->statistics()->attach($statistic, $couplingData);
        }
    }
}
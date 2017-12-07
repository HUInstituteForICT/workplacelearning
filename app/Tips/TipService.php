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
        $tip->threshold = $data['threshold'];
        $tip->tipText = $data['tipText'];
        $tip->multiplyBy100 = isset($data['multiplyBy100']);
        $tip->showInAnalysis = isset($data['showInAnalysis']);

        $statistic = (new Statistic)->findOrFail($data['statistic']['id']);
        $tip->statistic()->associate($statistic);

        $tip->save();

        return $tip;
    }

    public function enableCohorts(Tip $tip, array $data)
    {
        $tip->load('enabledCohorts');

        // Delete all current attached cohorts
        $tip->enabledCohorts()->detach();
        // Enable/attach all selected cohorts
        $tip->enabledCohorts()->attach($data['enabledCohorts']);

        return $tip;
    }
}
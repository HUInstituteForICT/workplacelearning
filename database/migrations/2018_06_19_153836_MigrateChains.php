<?php

use App\Chain;
use Illuminate\Database\Migrations\Migration;

class MigrateChains extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $laps = \App\LearningActivityProducing::orderBy('lap_id', 'ASC')->get();

        $lapsWithChain = 0;
        $startingLaps = 0;

//        echo "\nConverting old LAP chains to new format... \n";
        /** @var \App\LearningActivityProducing $activity */
        foreach ($laps as $activity) {
            $chain = null;
            if ($activity->previousLearningActivityProducing !== null) {
                $chain = $activity->previousLearningActivityProducing->chain;
            } elseif ($activity->nextLearningActivityProducing !== null) {
                $chain = $this->createChainForLap($activity);
                ++$startingLaps;
            }
            if ($chain !== null) {
                $activity->chain_id = $chain->id;
                $activity->save();
                ++$lapsWithChain;

                if (($lapsWithChain % 25) === 0) {
//                    echo '.';
                }
                if (($lapsWithChain % 250) === 0) {
//                    echo " - ${lapsWithChain} \n";
                }
            }
        }

//        echo "\n{$laps->count()} checked, {$lapsWithChain} in a chain, {$startingLaps} chains created\n";
    }

    private function createChainForLap(App\LearningActivityProducing $learningActivityProducing): Chain
    {
        /** @var \App\WorkplaceLearningPeriod $wplp */
        $wplp = $learningActivityProducing->workplaceLearningPeriod;
        $locale = $wplp->student->locale;
        $chain = new Chain();
        $chain->name = ($locale === 'nl' ? 'Keten' : 'Chain').' '.$learningActivityProducing->description;
        $chain->status = Chain::STATUS_BUSY;
        $chain->wplp_id = $learningActivityProducing->wplp_id;
        $chain->save();

        return $chain;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE learningactivityproducing SET chain_id = NULL');
        DB::statement('DELETE FROM chains');
    }
}

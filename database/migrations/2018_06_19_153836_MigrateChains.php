<?php

use App\Chain;
use Illuminate\Database\Migrations\Migration;

class MigrateChains extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $laps = \App\LearningActivityProducing::orderBy('lap_id', 'ASC')->get();

        $lapsWithChain = 0;
        $startingLaps = 0;

        echo "\nConverting old LAP chains to new format... \n";
        /** @var \App\LearningActivityProducing $activity */
        foreach ($laps as $activity) {
            $chain = null;
            if (null !== $activity->previousLearningActivityProducing) {
                $chain = $activity->previousLearningActivityProducing->chain;
            } elseif (null !== $activity->nextLearningActivityProducing) {
                $chain = $this->createChainForLap($activity);
                ++$startingLaps;
            }
            if (null !== $chain) {
                $activity->chain_id = $chain->id;
                $activity->save();
                ++$lapsWithChain;

                if (0 === ($lapsWithChain % 25)) {
                    echo '.';
                }
                if (0 === ($lapsWithChain % 250)) {
                    echo " - ${lapsWithChain} \n";
                }
            }
        }

        echo "\n{$laps->count()} checked, {$lapsWithChain} in a chain, {$startingLaps} chains created\n";
    }

    private function createChainForLap(\App\LearningActivityProducing $learningActivityProducing): Chain
    {
        /** @var \App\WorkplaceLearningPeriod $wplp */
        $wplp = $learningActivityProducing->workplaceLearningPeriod;
        $locale = $wplp->student->locale;
        $chain = new Chain();
        $chain->name = ('nl' === $locale ? 'Keten' : 'Chain').' '.$learningActivityProducing->description;
        $chain->status = Chain::STATUS_BUSY;
        $chain->wplp_id = $learningActivityProducing->wplp_id;
        $chain->save();

        return $chain;
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('UPDATE learningactivityproducing SET chain_id = NULL');
        DB::statement('DELETE FROM chains');
    }
}

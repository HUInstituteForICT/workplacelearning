<?php


namespace App\Tips;


use App\Cohort;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Tip
 *
 * @property boolean $multiplyBy100 Whether or not the percentage calculated should be multiplied by 100 in the getTipText
 * @property string $name Name of the tip
 * @property boolean $showInAnalysis Whether or not the tip should be displayed in analyses
 * @property integer $id ID of the tip
 * @property Statistic[]|Collection $statistics of the tip
 * @property float $threshold The threshold that determines whether the tip is applicable or not
 * @property string tipText The text including placeholders used for displaying the tip
 * @property Cohort[]|Collection enabledCohorts
 */
class Tip extends Model
{

    /**
     * The result will be newly cached after every call to the "isApplicable". This to counteract recalculating on a "getTipText" call.
     * We can safely assume the "getTipText" call immediately follows the "isApplicable" call.
     * @var float|int $cachedResult
     */
    private $cachedResult;

    public $timestamps = false;

    /**
     * The statistic used for this tip
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function statistics() {
        return $this->belongsToMany(Statistic::class, 'tip_coupled_statistic')
            ->using(TipCoupledStatistic::class)
            ->withPivot(['id', 'comparison_operator', 'threshold', 'multiplyBy100']);
    }

    /**
     * The cohorts this Tip is enabled for
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enabledCohorts() {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * Check if the tip is applicable
     * @param DataCollectorContainer $collector
     * @return bool
     */
    public function isApplicable(DataCollectorContainer $collector)
    {
        $applicable = true;
        $this->statistics->each(function(Statistic $statistic) use(&$applicable, $collector) {
            $statistic->setDataCollector($collector);
            $this->cachedResult[$statistic->pivot->id] = $statistic->calculate();

            $applicable = $statistic->pivot->passes($this->cachedResult[$statistic->pivot->id]);
            return $applicable;
        });


        return $applicable;
    }

    /**
     * Get the tipText with calculated data
     * @return string
     */
    public function getTipText()
    {
        $tipText = $this->tipText;
        $this->statistics->each(function(Statistic $statistic) use(&$tipText) {
            $percentageValue = $statistic->pivot->multiplyBy100 ? number_format($this->cachedResult[$statistic->pivot->id] * 100) : $this->cachedResult[$statistic->pivot->id];
            $tipText = str_replace(":value-{$statistic->pivot->id}", $percentageValue, $tipText);
        });

        return $tipText;
    }

    public function buildTextParameters() {
        return $this->statistics()->orderBy('tip_coupled_statistic.id', 'ASC')->get()->flatMap(function(Statistic $statistic) {
            return [$statistic->pivot->condition() => ":value-{$statistic->pivot->id}"];
        });
    }


}
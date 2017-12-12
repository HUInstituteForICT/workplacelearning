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
        return $this->belongsToMany(Statistic::class, 'tip_coupled_statistic')->using(TipCoupledStatistic::class);
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
        $this->statistic->setDataCollector($collector);
        $this->cachedResult = $this->statistic->calculate();

        return $this->cachedResult >= $this->threshold;
    }

    /**
     * Get the tipText with calculated data
     * @return string
     */
    public function getTipText()
    {
        $tipText = $this->tipText;
        $percentageValue = $this->multiplyBy100 ? number_format($this->cachedResult * 100) : $this->cachedResult;
        $tipText = str_replace(':percentage', $percentageValue, $tipText);

        return $tipText;
    }


}
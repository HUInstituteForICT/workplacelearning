<?php


namespace App\Tips;


use App\Cohort;
use App\Student;
use App\Tips\DataCollectors\Collector;
use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\Statistic;
use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticCalculationResultCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Tip
 *
 * @property string $name Name of the tip
 * @property boolean $showInAnalysis Whether or not the tip should be displayed in analyses
 * @property integer $id ID of the tip
 * @property Statistic[]|Collection $coupledStatistics of the tip
 * @property string $tipText The text including placeholders used for displaying the tip
 * @property Cohort[]|Collection $enabledCohorts
 * @property Like[]|Collection $likes
 */
class Tip extends Model
{

    public $timestamps = false;
    /**
     * The result will be newly cached after every call to the "isApplicable". This to counteract recalculating on a "getTipText" call.
     * We can safely assume the "getTipText" call immediately follows the "isApplicable" call.
     * @var StatisticCalculationResultCollection[] $cachedResultsCollection
     */
    protected $cachedResultsCollection;

    /**
     * The likes this Tip has given by Students
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesByStudent(Student $student)
    {
        return $this->hasMany(Like::class)->where('student_id', '=', $student->student_id);
    }

    /**
     * The cohorts this Tip is enabled for
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enabledCohorts()
    {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * Check if the tip is applicable
     * @param Collector $collector
     * @return bool
     */
    public function isApplicable(Collector $collector)
    {
        $applicable = true;
        $this->coupledStatistics->each(function (TipCoupledStatistic $tipCoupledStatistic) use (&$applicable, $collector) {
            $tipCoupledStatistic->statistic->setCollector($collector);

            $this->cachedResultsCollection[$tipCoupledStatistic->id] = $tipCoupledStatistic->statistic->calculate();

            $applicable = $tipCoupledStatistic->passes($this->cachedResultsCollection[$tipCoupledStatistic->id]);

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
        $this->coupledStatistics->each(function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipText) {
            $percentageValues = [];
            /** @var StatisticCalculationResult $calculationResult */
            foreach ($this->cachedResultsCollection[$tipCoupledStatistic->id]->getResults() as $calculationResult) {
                if ($calculationResult->hasPassed()) {
                    $percentageValues[] = $tipCoupledStatistic->multiplyBy100 ?
                        number_format($calculationResult->getResult() * 100) . '%' :
                        number_format($calculationResult->getResult(), 3) . '%';
                }
            }


            if ($tipCoupledStatistic->statistic instanceof PredefinedStatistic) {
                $entityNames = array_map(function (StatisticCalculationResult $calculationResult) {
                    return $calculationResult->getEntityName();
                }, $this->cachedResultsCollection[$tipCoupledStatistic->id]->getResults());
                $tipText = str_replace(":statistic-name-{$tipCoupledStatistic->id}",
                    implode(', ', $entityNames), $tipText);

            }
            $tipText = str_replace(":statistic-{$tipCoupledStatistic->id}", implode(', ', $percentageValues), $tipText);

        });

        return $tipText;
    }

    /**
     * The coupled statistics used for this tip
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupledStatistics()
    {
        return $this->hasMany(TipCoupledStatistic::class);
    }
}
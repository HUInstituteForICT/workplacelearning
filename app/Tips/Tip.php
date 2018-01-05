<?php


namespace App\Tips;


use App\Cohort;
use App\Student;
use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\Statistic;
use App\Tips\Statistics\StatisticCalculationResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Tip
 *
 * @property boolean $multiplyBy100 Whether or not the percentage calculated should be multiplied by 100 in the getTipText
 * @property string $name Name of the tip
 * @property boolean $showInAnalysis Whether or not the tip should be displayed in analyses
 * @property integer $id ID of the tip
 * @property CustomStatistic[]|Collection $statistics of the tip
 * @property float $threshold The threshold that determines whether the tip is applicable or not
 * @property string $tipText The text including placeholders used for displaying the tip
 * @property Cohort[]|Collection $enabledCohorts
 * @property Like[]|Collection $likes
 */
class Tip extends Model
{

    /**
     * The result will be newly cached after every call to the "isApplicable". This to counteract recalculating on a "getTipText" call.
     * We can safely assume the "getTipText" call immediately follows the "isApplicable" call.
     * @var StatisticCalculationResult[] $cachedResult
     */
    protected $cachedResult;

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
     * The likes this Tip has given by Students
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesByStudent(Student $student) {
        return $this->hasMany(Like::class)->where('student_id', '=', $student->student_id);
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
        $this->statistics->each(function (Statistic $statistic) use (&$applicable, $collector) {
            $statistic->setDataCollectorContainer($collector);

            $this->cachedResult[$statistic->pivot->id] = $statistic->calculate();

            $applicable = $statistic->pivot->passes($this->cachedResult[$statistic->pivot->id]->getResult());
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
        $this->statistics->each(function (Statistic $statistic) use (&$tipText) {
            $percentageValue = $statistic->pivot->multiplyBy100 ?
                number_format($this->cachedResult[$statistic->pivot->id]->getResult() * 100) :
                $this->cachedResult[$statistic->pivot->id]->getResult();
            if($statistic instanceof PredefinedStatistic) {
                $tipText = str_replace(":value-name-{$statistic->pivot->id}", $this->cachedResult[$statistic->pivot->id]->getEntityName(), $tipText);
            }
            $tipText = str_replace(":value-{$statistic->pivot->id}", $percentageValue, $tipText);

        });

        return $tipText;
    }

    public function buildTextParameters() {
        return $this->statistics()->orderBy('tip_coupled_statistic.id', 'ASC')->get()->flatMap(function (
            Statistic $statistic
        ) {
            return [
                $statistic->pivot->condition() => [
                    "value" => ":value-{$statistic->pivot->id}",
                    "valueName" => ($statistic instanceof PredefinedStatistic ? ":value-name-{$statistic->pivot->id}" : trans('-'))
                    ]
                ];
        });
    }


}
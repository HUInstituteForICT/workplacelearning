<?php

namespace App\Tips\Statistics;


use App\EducationProgramType;
use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\Exceptions\SingleTableInheritanceInvalidAttributesException;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

/**
 * @property integer $id The id of the statistic
 * @property string $name The name of this statistic
 * @property EducationProgramType $educationProgramType the education program type of this statistic. Some data is only available for certain types, therefore a distinction is necessary.
 * @property TipCoupledStatistic $pivot
 */
class Statistic extends Model
{
    use SingleTableInheritanceTrait;

    protected $table = 'statistics';

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [CustomStatistic::class, PredefinedStatistic::class];

    protected static $persisted = ['name', 'education_program_type_id'];

    // Injected into StatisticVariables,  or PredefinedStatistic that use a dataCollector
    /** @var DataCollectorContainer $dataCollectorContainer */
    protected $dataCollectorContainer;

    // Disable timestamps
    public $timestamps = false;

    // Hide it from API because we already have the education_program_type relation in the JSON
    protected $hidden = ['education_program_type_id'];

    /**
     * @throws \Exception
     */
    public function calculate() { throw new \Exception("Should be called in subclass"); }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupledStatistics() {
        return $this->hasMany(TipCoupledStatistic::class, 'statistic_id');

//        return $this->belongsToMany(Tip::class, 'tip_coupled_statistic')
//            ->using(TipCoupledStatistic::class)
//            ->withPivot(['id', 'comparison_operator', 'threshold', 'multiplyBy100']);
    }



    /**
     * Relation to the EducationProgramType
     * Certain data is only available to certain education program types (acting, producing)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function educationProgramType() {
        return $this->belongsTo(EducationProgramType::class, 'education_program_type_id', 'eptype_id');
    }

    /**
     * Set the dataCollector used by certain StatisticVariables and PredefinedStatistics
     *
     * @param DataCollectorContainer $dataCollectorContainer
     */
    public function setDataCollectorContainer(DataCollectorContainer $dataCollectorContainer)
    {
        $this->dataCollectorContainer = $dataCollectorContainer;
    }

}
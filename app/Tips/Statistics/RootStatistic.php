<?php
// TODO Change RootStatistic to Statistic and Statistic to CalculatedStatistic

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
class RootStatistic extends Model
{
    use SingleTableInheritanceTrait;

    public function setFilteredAttributes(array $attributes) {
        $persistedAttributes = $this->getPersistedAttributes();
        if (empty($persistedAttributes)) {
            $filteredAttributes = $attributes;
        } else {
            // The query often include a 'select *' from the table which will return null for columns that are not persisted.
            // If any of those columns are non-null then we need to filter them our or throw and exception if configured.
            // array_flip is a cute way to do diff/intersection on keys by a non-associative array
            $extraAttributes = array_filter(array_diff_key($attributes, array_flip($persistedAttributes)), function($value) {
                return !is_null($value);
            });
            if (!empty($extraAttributes) && $this->getThrowInvalidAttributeExceptions()) {
                throw new SingleTableInheritanceInvalidAttributesException("Cannot construct " . get_called_class() . ".", $extraAttributes);
            }
            $filteredAttributes = array_intersect_key($attributes, array_flip($persistedAttributes));
        }
        // All pivot attributes start with 'pivot_'
        // Add pivot attributes back in
        $filteredAttributes += $this->getPivotAttributeNames($attributes);

        $this->setRawAttributes($filteredAttributes, true);
    }

    protected function getPivotAttributeNames($attributes)
    {
        $pivots = [];
        foreach ($attributes as $key => $value) {
            if (starts_with($key, 'pivot_')) {
                array_set($pivots, $key, $value);
            }
        }
        return $pivots;
    }

    public function getForeignKey()
    {
        return 'statistic_id';
    }

    protected $table = "statistics";

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [CustomStatistic::class, PredefinedStatistic::class];

    protected static $persisted = ['name', 'education_program_type_id'];

    // Injected into StatisticVariables or PredefinedStatistic that use a dataCollector
    protected $dataCollector;

    // Disable timestamps
    public $timestamps = false;

    public function calculate() { throw new \Exception("Should be called in subclass"); }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tips() {
        return $this->belongsToMany(Tip::class, 'tip_coupled_statistic')
            ->using(TipCoupledStatistic::class)
            ->withPivot(['id', 'comparison_operator', 'threshold', 'multiplyBy100']);
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
     * @param DataCollectorContainer $dataCollector
     */
    public function setDataCollector($dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

}
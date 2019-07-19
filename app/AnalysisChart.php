<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\AnalysisChart.
 *
 * @property Analysis           $analysis
 * @property ChartType          $type
 * @property Collection|Label[] $labels
 * @property int                $id
 * @property int                $analysis_id
 * @property int                $type_id
 * @property string             $label
 * @property mixed              $x_label
 * @property mixed              $y_label
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart whereAnalysisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart whereTypeId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalysisChart query()
 */
class AnalysisChart extends Model
{
    protected $table = 'chart';
    public $timestamps = false;
    protected $fillable = array('label');

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(Analysis::class, 'analysis_id');
    }

    public function type(): HasOne
    {
        return $this->hasOne(ChartType::class, 'id', 'type_id');
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class, 'chart_id');
    }

    /**
     * $this->x_label.
     */
    public function getXLabelAttribute(): Label
    {
        return $this->labels->where('type', 'x')->first();
    }

    /**
     * $this->y_label.
     */
    public function getYLabelAttribute(): Label
    {
        return $this->labels->where('type', 'y')->first();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\DashboardChart.
 *
 * @property int                             $position
 * @property int                             $chart_id
 * @property AnalysisChart                   $chart
 * @property int                             $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DashboardChart whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DashboardChart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DashboardChart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DashboardChart wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DashboardChart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DashboardChart extends Model
{
    protected $table = 'dashboard_charts';
    protected $fillable = array('label', 'position');

    public function chart(): HasOne
    {
        return $this->hasOne(AnalysisChart::class, 'id', 'chart_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int           $position
 * @property int           $chart_id
 * @property AnalysisChart $chart
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

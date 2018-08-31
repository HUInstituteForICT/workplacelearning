<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardChart extends Model
{
    protected $table = 'dashboard_charts';
    protected $fillable = array('label', 'position');

    public function chart()
    {
        return $this->hasOne('App\AnalysisChart', 'id', 'chart_id');
    }
}

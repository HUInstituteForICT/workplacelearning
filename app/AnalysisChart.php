<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisChart extends Model
{
    protected $table = 'chart';
    public $timestamps = false;
    protected $fillable = array('label');

    public function analysis()
    {
        return $this->belongsTo('App\Analysis', 'analysis_id');
    }

    public function type()
    {
        return $this->hasOne('App\ChartType', 'id', 'type_id');
    }

    public function labels()
    {
        return $this->hasMany('App\Label', 'chart_id');
    }

    /**
     * $this->x_label.
     *
     * @param $value
     */
    public function getXLabelAttribute($value)
    {
        return $this->labels()->where('type', 'x')->first();
    }

    /**
     * $this->y_label.
     *
     * @param $value
     */
    public function getYLabelAttribute($value)
    {
        return $this->labels()->where('type', 'y')->first();
    }
}

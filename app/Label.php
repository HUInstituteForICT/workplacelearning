<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Label.
 *
 * @property int                $id
 * @property int                $chart_id
 * @property string             $name
 * @property string             $type
 * @property \App\AnalysisChart $chart
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Label whereChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Label whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Label whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Label whereType($value)
 * @mixin \Eloquent
 */
class Label extends Model
{
    protected $table = 'labels';
    public $timestamps = false;
    protected $fillable = array('name', 'type', 'chart_id'); // chart_id should not be here

    public function chart()
    {
        return $this->belongsTo('App\AnalysisChart');
    }
}

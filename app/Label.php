<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

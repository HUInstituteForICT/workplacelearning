<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisChart extends Model 
{

    protected $table = 'chart';
    public $timestamps = false;
    protected $fillable = array('label');

    public function analyse()
    {
        return $this->hasOne('\Analysis', 'analysis_id');
    }

    public function type()
    {
        return $this->hasOne('\AnalysisChart', 'type_id');
    }

}
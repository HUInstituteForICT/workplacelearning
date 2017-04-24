<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model 
{

    protected $table = 'labels';
    public $timestamps = false;
    protected $fillable = array('name');

    public function chart()
    {
        return $this->hasOne('\AnalysisChart');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model 
{

    protected $table = 'analyses';
    public $timestamps = false;
    protected $fillable = array('name', 'query', 'cache_duration');

    public function charts()
    {
        return $this->hasMany('App\AnalysisChart');
    }

}
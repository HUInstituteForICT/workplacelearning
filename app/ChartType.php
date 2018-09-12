<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartType extends Model
{
    protected $table = 'chart_types';
    public $timestamps = false;
    protected $fillable = array('name');
}

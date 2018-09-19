<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug
 */
class ChartType extends Model
{
    protected $table = 'chart_types';
    public $timestamps = false;
    protected $fillable = array('name');
}

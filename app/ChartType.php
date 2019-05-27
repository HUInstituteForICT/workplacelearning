<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChartType.
 *
 * @property string $slug
 * @property int    $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType whereSlug($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChartType query()
 */
class ChartType extends Model
{
    protected $table = 'chart_types';
    public $timestamps = false;
    protected $fillable = array('name');
}

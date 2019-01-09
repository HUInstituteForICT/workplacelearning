<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Parameter.
 *
 * @property int                             $id
 * @property int                             $template_id
 * @property string                          $name
 * @property string                          $type_name
 * @property string|null                     $table
 * @property string|null                     $column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Template                   $template
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Parameter extends Model
{
    protected $table = 'parameters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type_name',
        'table',
        'column',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}

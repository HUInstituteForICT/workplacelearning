<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Template.
 *
 * @property int                                                       $id
 * @property string                                                    $name
 * @property string                                                    $query
 * @property \Illuminate\Support\Carbon|null                           $created_at
 * @property \Illuminate\Support\Carbon|null                           $updated_at
 * @property string|null                                               $description
 * @property \Illuminate\Database\Eloquent\Collection|\App\Parameter[] $parameters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Template query()
 */
class Template extends Model
{
    protected $table = 'templates';
    protected $primaryKey = 'id';
    public $parameters = [];

    protected $fillable = [
        'name',
        'description',
        'query',
    ];

    public function getParameters()
    {
        return (new \App\Parameter())->where('template_id', $this->id)->get();
    }

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }
}

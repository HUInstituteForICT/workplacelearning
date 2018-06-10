<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';
    public $primaryKey = 'id';
    public $parameters = [];

    protected $fillable = [
        'name',
        'query'
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

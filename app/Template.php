<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';
    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'query'
    ];

}

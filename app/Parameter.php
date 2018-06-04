<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{

    protected $table = 'parameters';
    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type_name',
        'table',
        'column'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

}

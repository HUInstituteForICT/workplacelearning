<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    public $timestamps = false;

    protected $table = 'accesslog';
}

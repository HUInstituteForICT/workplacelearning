<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * @property string $log
 * @property bool $fixed
 * @property int $id
 */
class ReactLog extends Model
{
    protected $fillable = ['log', 'fixed'];
}
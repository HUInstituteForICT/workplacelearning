<?php
/**
 * This file (Status.php) was created on 02/12/2017 at 14:02.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Status.
 *
 * @property string                                                                    $status_label
 * @property int                                                                       $status_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningActivityProducing[] $learningActivityProducing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereStatusLabel($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status query()
 */
class Status extends Model
{
    // Override the table used for the User Model
    protected $table = 'status';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'status_id';

    // Default
    protected $fillable = [
        'status_id',
        'status_label',
    ];

    public function learningActivityProducing()
    {
        return $this->hasMany(LearningActivityProducing::class, 'status_id', 'status_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['learningActivityProducing'];
    }

    public function isFinished(): bool
    {
        return \in_array(strtolower($this->status_label), ['afgerond', 'finished']);
    }

    public function isBusy(): bool
    {
        return \in_array(strtolower($this->status_label), ['mee bezig', 'busy']);
    }

    public function isTransferred(): bool
    {
        return \in_array(strtolower($this->status_label), ['overgedragen', 'transferred']);
    }
}

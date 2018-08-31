<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $learninggoal_id
 * @property string $learninggoal_label
 */
class LearningGoal extends Model
{
    // Override the table used for the User Model
    protected $table = 'learninggoal';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'learninggoal_id';

    // Default
    protected $fillable = [
        'learninggoal_id',
        'learninggoal_label',
        'wplp_id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->description = ''; // text fields in mysql can't have default value, seems like a proper fix
        parent::__construct($attributes);
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityActing()
    {
        return $this->belongsTo('App\learningActivityActing', 'learninggoal_id', 'learninggoal_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['workplaceLearningPeriod', 'learningActivityActing'];
    }
}

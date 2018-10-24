<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'disk_filename',
        'mime',
    ];

    public function learningActivity(): BelongsTo
    {
        return $this->belongsTo(LearningActivityActing::class, 'learning_activity_acting_id', 'laa_id');
    }
}

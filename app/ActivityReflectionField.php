<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityReflectionField extends Model
{
    public $timestamps = false;

    public function activityReflection(): BelongsTo
    {
        return $this->belongsTo(ActivityReflection::class);
    }
}

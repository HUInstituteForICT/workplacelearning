<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                         $id
 * @property string                      $name
 * @property LearningActivityProducing[] $activities
 * @property int                         $status
 * @property int                         $wplp_id
 */
class Chain extends Model
{
    public $timestamps = false;

    const STATUS_BUSY = 0;
    const STATUS_FINISHED = 1;

    public function activities(): HasMany
    {
        return $this->hasMany(LearningActivityProducing::class, 'chain_id', 'id')->orderBy('date', 'ASC');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }
}

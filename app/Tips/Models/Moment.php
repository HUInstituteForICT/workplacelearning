<?php

namespace App\Tips\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $rangeStart
 * @property int $rangeEnd
 * @property Tip $tip
 * @property int $tip_id
 */
class Moment extends Model
{
    public $timestamps = false;

    public function tip(): BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }
}

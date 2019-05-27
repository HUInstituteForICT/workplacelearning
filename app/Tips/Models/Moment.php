<?php

namespace App\Tips\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Tips\Models\Moment.
 *
 * @property int $id
 * @property int $rangeStart
 * @property int $rangeEnd
 * @property Tip $tip
 * @property int $tip_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment whereRangeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment whereRangeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment whereTipId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Moment query()
 */
class Moment extends Model
{
    public $timestamps = false;

    public function tip(): BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }
}

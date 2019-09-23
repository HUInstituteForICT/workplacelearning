<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Chain.
 *
 * @property int                                    $id
 * @property string                                 $name
 * @property Collection|LearningActivityProducing[] $activities
 * @property int                                    $status
 * @property int                                    $wplp_id
 * @property \App\WorkplaceLearningPeriod           $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain whereWplpId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chain query()
 */
class Chain extends Model
{
    public $timestamps = false;

    public const STATUS_BUSY = 0;
    public const STATUS_FINISHED = 1;

    public function activities(): HasMany
    {
        return $this->hasMany(LearningActivityProducing::class, 'chain_id', 'id')->orderBy('date', 'ASC');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function hours(): float
    {
        return array_reduce($this->activities->all(), function (float $hours, LearningActivityProducing $activity) {
            $hours += $activity->duration;

            return $hours;
        }, 0);
    }
}

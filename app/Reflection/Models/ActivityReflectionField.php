<?php

namespace App\Reflection\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\ActivityReflectionField.
 *
 * @property string $name
 * @property string $value
 *
 * @method static findOrFail(int $id)
 *
 * @property \App\Reflection\Models\ActivityReflection $activityReflection
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField query()
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $activity_reflection_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField whereActivityReflectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflectionField whereValue($value)
 */
class ActivityReflectionField extends Model
{
    public $timestamps = false;

    public function activityReflection(): BelongsTo
    {
        return $this->belongsTo(ActivityReflection::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * @property string type
 */
class ActivityReflection extends Model
{
    public $timestamps = false;

    public const LEARNING_ACTIVITY_ACTING = 'acting';
    public const LEARNING_ACTIVITY_PRODUCING = 'producing';

    public const TYPES = ['STARR', 'KORTHAGEN', 'ABCD', 'PDCA', 'CUSTOM'];
    public const READABLE_TYPES = ['STARR' => 'STARR', 'KORTHAGEN' => 'Korthagen', 'ABCD' => 'ABCD', 'PDCA' => 'PDCA', 'CUSTOM' => 'Custom'];

    public function fields(): HasMany
    {
        return $this->hasMany(ActivityReflectionField::class);
    }

    /**
     * @throws RuntimeException
     */
    public function learningActivity(): BelongsTo
    {
        if ($this->type === self::LEARNING_ACTIVITY_ACTING) {
            return $this->belongsTo(LearningActivityActing::class, 'learning_activity_id', 'laa_id');
        }

        if ($this->type === self::LEARNING_ACTIVITY_PRODUCING) {
            return $this->belongsTo(LearningActivityProducing::class, 'learning_activity_id', 'lap_id');
        }

        throw new RuntimeException("ActivityReflection with type {$this->type} cannot be related to a learning activity");
    }
}

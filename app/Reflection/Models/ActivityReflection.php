<?php

declare(strict_types=1);

namespace App\Reflection\Models;

use App\Interfaces\LearningActivityInterface;
use App\LearningActivityActing;
use App\LearningActivityProducing;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * App\ActivityReflection.
 *
 * @property int                                                                                       $id
 * @property int                                                                                       $learning_activity_id
 * @property string                                                                                    $learning_activity_type
 * @property string                                                                                    $reflection_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Reflection\Models\ActivityReflectionField[] $fields
 * @property LearningActivityInterface                                                                 $learningActivity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection whereLearningActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection whereLearningActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityReflection whereReflectionType($value)
 * @mixin Eloquent
 */
class ActivityReflection extends Model
{
    public $timestamps = false;

    public const LEARNING_ACTIVITY_ACTING = 'acting';
    public const LEARNING_ACTIVITY_PRODUCING = 'producing';
    public const LEARNING_ACTIVITY_TYPE = [LearningActivityActing::class    => self::LEARNING_ACTIVITY_ACTING,
                                           LearningActivityProducing::class => self::LEARNING_ACTIVITY_PRODUCING,
    ];

    public const TYPES = ['STARR', 'KORTHAGEN', 'ABCD', 'CUSTOM'];
    public const READABLE_TYPES = ['STARR'     => 'STARR',
                                   'KORTHAGEN' => 'Korthagen',
                                   'ABCD'      => 'ABCD',
                                   'CUSTOM'    => 'Custom',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(ActivityReflectionField::class);
    }

    /**
     * @throws RuntimeException
     */
    public function learningActivity(): BelongsTo
    {
        if ($this->learning_activity_type === self::LEARNING_ACTIVITY_ACTING) {
            return $this->belongsTo(LearningActivityActing::class, 'learning_activity_id', 'laa_id');
        }

        if ($this->learning_activity_type === self::LEARNING_ACTIVITY_PRODUCING) {
            return $this->belongsTo(LearningActivityProducing::class, 'learning_activity_id', 'lap_id');
        }

        throw new RuntimeException("ActivityReflection with type {$this->learning_activity_type} cannot be related to a learning activity");
    }

    public function renderableFields(): array
    {
        return $this->fields->reduce(function (array $carry, ActivityReflectionField $field) {
            $carry[$field->name] = $field->value;

            return $carry;
        }, []);
    }
}

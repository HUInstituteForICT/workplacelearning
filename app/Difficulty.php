<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Difficulty.
 *
 * @property string                                                                    $difficulty_label
 * @property int                                                                       $difficulty_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningActivityProducing[] $learningActivityProducing
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Difficulty whereDifficultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Difficulty whereDifficultyLabel($value)
 * @mixin \Eloquent
 */
class Difficulty extends Model
{
    // Override the table used for the User Model
    protected $table = 'difficulty';
    // Disable using created_at and updated_doat columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'difficulty_id';

    // Default
    protected $fillable = [
        'difficulty_id',
        'difficulty_label',
    ];

    public function learningActivityProducing(): HasMany
    {
        return $this->hasMany(\App\LearningActivityProducing::class, 'difficulty_id', 'difficulty_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['learningActivityProducing'];
    }

    public function isEasy(): bool
    {
        return \in_array(strtolower($this->difficulty_label), ['makkelijk', 'easy']);
    }

    public function isHard(): bool
    {
        return \in_array(strtolower($this->difficulty_label), ['moeilijk', 'hard']);
    }

    public function isAverage(): bool
    {
        return \in_array(strtolower($this->difficulty_label), ['gemiddeld', 'average']);
    }
}

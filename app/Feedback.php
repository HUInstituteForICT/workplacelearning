<?php

declare(strict_types=1);
/**
 * This file (Deadline.php) was created on 08/18/2016 at 15:30.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Feedback.
 *
 * @property learningActivityProducing $learningActivityProducing
 * @property int                       $fb_id
 * @property int                       $notfinished
 * @property string                    $initiative
 * @property string                    $progress_satisfied
 * @property string                    $support_requested
 * @property string                    $supported_provided_wp
 * @property string                    $nextstep_self
 * @property string                    $support_needed_wp
 * @property string                    $support_needed_ed
 * @property int                       $learningactivity_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereFbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereInitiative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereLearningactivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereNextstepSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereNotfinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereProgressSatisfied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereSupportNeededEd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereSupportNeededWp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereSupportRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereSupportedProvidedWp($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback query()
 */
class Feedback extends Model
{
    // Override the table used for the User Model
    protected $table = 'feedback';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'fb_id';

    // Default
    protected $fillable = [
        'fb_id',
        'learningactivity_id',
        'notfinished',
        'initiative',
        'progress_satisfied',
        'support_requested',
        'supported_provided_wp',
        'nextstep_self',
        'support_needed_wp',
        'support_needed_ed',
    ];

    public function isSaved(): bool
    {
        if (!$this->notfinished) {
            return false;
        }

        return $this->notfinished !== '';
    }

    public function learningActivityProducing(): BelongsTo
    {
        return $this->belongsTo(LearningActivityProducing::class, 'learningactivity_id', 'lap_id');
    }
}

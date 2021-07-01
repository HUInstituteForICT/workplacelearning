<?php

declare(strict_types=1);
/**
 * This file (Deadline.php) was created on 08/18/2016 at 15:30.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Feedback.
 *
 * @property genericLearningActivity $genericLearningActivity
 * @property int                       $fb_id
 * @property string                    $notfinished
 * @property string                    $initiative
 * @property string                    $progress_satisfied
 * @property string                    $support_requested
 * @property string                    $supported_provided_wp
 * @property string                    $nextstep_self
 * @property string                    $support_needed_wp
 * @property string                    $support_needed_ed
 * @property int                       $genericlearningactivity_id
 *
 * @method static Builder|Feedback whereFbId($value)
 * @method static Builder|Feedback whereInitiative($value)
 * @method static Builder|Feedback whereGenericLearningActivityId($value)
 * @method static Builder|Feedback whereNextstepSelf($value)
 * @method static Builder|Feedback whereNotfinished($value)
 * @method static Builder|Feedback whereProgressSatisfied($value)
 * @method static Builder|Feedback whereSupportNeededEd($value)
 * @method static Builder|Feedback whereSupportNeededWp($value)
 * @method static Builder|Feedback whereSupportRequested($value)
 * @method static Builder|Feedback whereSupportedProvidedWp($value)
 * @mixin Eloquent
 *
 * @method static Builder|Feedback newModelQuery()
 * @method static Builder|Feedback newQuery()
 * @method static Builder|Feedback query()
 */
class Feedback extends Model
{
    // Override the table used for the User Model
    public $timestamps = false;

    // Disable using created_at and updated_at columns

    protected $table = 'feedback';

    // Override the primary key column

    protected $primaryKey = 'fb_id';

    // Default
    protected $fillable = [
        'fb_id',
        'genericlearningactivity_id',
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

    public function genericLearningActivity(): BelongsTo
    {
        return $this->belongsTo(GenericLearningActivity::class, 'genericlearningactivity_id', 'gla_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Evidence.
 *
 * @property string disk_filename
 * @mixin \Eloquent
 *
 * @property int                         $id
 * @property int                         $learning_activity_acting_id
 * @property string                      $filename
 * @property string                      $mime
 * @property \App\LearningActivityActing $learningActivity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereDiskFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereLearningActivityActingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereMime($value)
 *
 * @property string $disk_filename
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence query()
 */
class Evidence extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'disk_filename',
        'mime',
    ];

    public function learningActivity(): BelongsTo
    {
        return $this->belongsTo(LearningActivityActing::class, 'learning_activity_acting_id', 'laa_id');
    }
}

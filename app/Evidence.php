<?php

declare(strict_types=1);

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
 * @property int                         $genericlearningactivity_id
 * @property string                      $filename
 * @property string                      $mime
 * @property \App\GenericLearningActivity $genericLearningActivity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereDiskFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evidence whereGenericLearningActivityId($value)
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

    public function genericLearningActivity(): BelongsTo
    {
        return $this->belongsTo(GenericLearningActivity::class, 'genericlearningactivity_id', 'gla_id');
    }
}

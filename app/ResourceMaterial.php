<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\ResourceMaterial.
 *
 * @property string                                                                    $rm_label
 * @property int                                                                       $wplp_id
 * @property int                                                                       $rm_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\GenericLearningActivity[] $genericLearningActivity
 * @property \App\WorkplaceLearningPeriod                                              $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial whereRmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial whereRmLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial whereWplpId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourceMaterial query()
 */
class ResourceMaterial extends Model
{
    // Override the table used for the User Model
    protected $table = 'resourcematerial';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'rm_id';

    // Default
    protected $fillable = [
        'rm_label',
        'wplp_id',
    ];

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function genericLearningActivity(): HasMany
    {
        return $this->hasMany(GenericLearningActivity::class, 'res_material_id', 'rm_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['workplaceLearningPeriod', 'genericLearningActivity'];
    }
}

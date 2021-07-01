<?php

declare(strict_types=1);
/**
 * This file (Samenwerkingsverband.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use App\Interfaces\HasLabelProperty;
use App\Interfaces\IsTranslatable;
use App\Traits\TranslatableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ResourcePerson.
 *
 * @property int                            $rp_id
 * @property string                         $person_label
 * @property int                            $wplp_id
 * @property int                            $ep_id
 * @property int|null                       $cohort_id
 * @property \App\Cohort|null               $cohort
 * @property \App\EducationProgram|null     $educationProgram
 * @property \App\GenericLearningActivity   $genericLearningActivity
 * @property \App\WorkplaceLearningPeriod   $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson whereEpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson wherePersonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson whereRpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson whereWplpId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ResourcePerson query()
 */
class ResourcePerson extends Model implements HasLabelProperty, IsTranslatable
{
    use TranslatableEntity;

    // Override the table used for the User Model
    protected $table = 'resourceperson';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'rp_id';

    // Default
    protected $fillable = [
        'rp_id',
        'person_label',
        'ep_id',
        'wplp_id',
        'cohort_id',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function genericLearningActivity(): BelongsTo
    {
        return $this->belongsTo(GenericLearningActivity::class, 'rp_id', 'res_person_id');
    }

    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'ep_id', 'ep_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['cohort', 'workplaceLearningPeriod', 'genericLearningActivity', 'educationProgram'];
    }

    public function getLabel(): string
    {
        return $this->person_label ?? 'Unknown person';
    }

    public function isAlone(): bool
    {
        return in_array(strtolower($this->person_label), ['alleen', 'alone']);
    }
}

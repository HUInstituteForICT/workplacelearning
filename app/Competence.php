<?php

namespace App;

use App\Interfaces\HasLabelProperty;
use App\Interfaces\IsTranslatable;
use App\Traits\TranslatableEntity;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Competence.
 *
 * @property int                                                                    $competence_id
 * @property string                                                                 $competence_label
 * @property int|null                                                               $educationprogram_id
 * @property int|null                                                               $cohort_id
 * @property \App\Cohort|null                                                       $cohort
 * @property \App\EducationProgram|null                                             $educationProgram
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningActivityActing[] $learningActivityActing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence whereCompetenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence whereCompetenceLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence whereEducationprogramId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Competence query()
 */
class Competence extends Model implements HasLabelProperty, IsTranslatable
{
    use TranslatableEntity;

    // Override the table used for the User Model
    protected $table = 'competence';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'competence_id';

    // Default
    protected $fillable = [
        'competence_id',
        'competence_label',
        'educationprogram_id',
        'cohort_id',
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class, 'educationprogram_id', 'ep_id');
    }

    public function learningActivityActing()
    {
        return $this->belongsToMany(\App\LearningActivityActing::class, 'activityforcompetence', 'competence_id', 'learningactivity_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['cohort', 'educationProgram', 'learningActivityActing'];
    }

    public function getLabel(): string
    {
        return $this->competence_label ?? 'Unknown competence';
    }
}

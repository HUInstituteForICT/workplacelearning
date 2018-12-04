<?php

namespace App;

use App\Interfaces\LearningActivityInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\LearningActivityActing.
 *
 * @property int                                                      $laa_id
 * @property int                                                      $wplp_id
 * @property \DateTime                                                $date
 * @property int                                                      $timeslot_id
 * @property string                                                   $situation
 * @property string                                                   $lessonslearned
 * @property string                                                   $support_wp
 * @property string                                                   $support_ed
 * @property int                                                      $res_person_id
 * @property int                                                      $res_material_id
 * @property string                                                   $res_material_detail
 * @property int                                                      $learninggoal_id
 * @property string                                                   $evidence_filename
 * @property string                                                   $evidence_disk_filename
 * @property string                                                   $evidence_mime
 * @property Timeslot                                                 $timeslot
 * @property ResourcePerson                                           $resourcePerson
 * @property ResourceMaterial                                         $resourceMaterial
 * @property LearningGoal                                             $learningGoal
 * @property Collection|Competence[]                                  $competence
 * @property \Illuminate\Database\Eloquent\Collection|\App\Evidence[] $evidence
 * @property \App\WorkplaceLearningPeriod                             $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereLaaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereLearninggoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereLessonslearned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereResMaterialDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereResMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereResPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereSituation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereSupportEd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereSupportWp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityActing whereWplpId($value)
 * @mixin \Eloquent
 */
class LearningActivityActing extends Model implements LearningActivityInterface
{
    // Override the table used for the User Model
    protected $table = 'learningactivityacting';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'laa_id';

    // Default
    protected $fillable = [
        'laa_id',
        'wplp_id',
        'date',
        'timeslot_id',
        'situation',
        'lessonslearned',
        'support_wp',
        'support_ed',
        'res_person_id',
        'res_material_id',
        'res_material_detail',
        'learninggoal_id',
    ];

    public function learningGoal(): BelongsTo
    {
        return $this->belongsTo(LearningGoal::class, 'learninggoal_id', 'learninggoal_id');
    }

    public function competence(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'activityforcompetence', 'learningactivity_id', 'competence_id');
    }

    public function timeslot(): BelongsTo
    {
        return $this->belongsTo(Timeslot::class, 'timeslot_id', 'timeslot_id');
    }

    public function resourcePerson(): BelongsTo
    {
        return $this->belongsTo(ResourcePerson::class, 'res_person_id', 'rp_id');
    }

    public function resourceMaterial(): BelongsTo
    {
        return $this->belongsTo(ResourceMaterial::class, 'res_material_id', 'rm_id');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['learningGoal', 'competence', 'timeslot', 'resourcePerson', 'resourceMaterial'];
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class, 'learning_activity_acting_id', 'laa_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int       $laa_id
 * @property int       $wplp_id
 * @property \DateTime $date
 * @property int       $timeslot_id
 * @property string    $situation
 * @property string    $lessonslearned
 * @property string    $support_wp
 * @property string    $support_ed
 * @property int       $res_person_id
 * @property int       $res_material_id
 * @property string    $res_material_detail
 * @property int       $learninggoal_id
 * @property string    $evidence_filename
 * @property string    $evidence_disk_filename
 * @property string    $evidence_mime
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

    public function learningGoal()
    {
        return $this->hasOne(\App\LearningGoal::class, 'learninggoal_id', 'learninggoal_id');
    }

    public function competence()
    {
        return $this->belongsToMany(\App\Competence::class, 'activityforcompetence', 'learningactivity_id', 'competence_id');
    }

    public function timeslot()
    {
        return $this->belongsTo(\App\Timeslot::class, 'timeslot_id', 'timeslot_id');
    }

    public function resourcePerson()
    {
        return $this->hasOne(\App\ResourcePerson::class, 'rp_id', 'res_person_id');
    }

    public function resourceMaterial()
    {
        return $this->hasOne(\App\ResourceMaterial::class, 'rm_id', 'res_material_id');
    }

    /**
     * Used for display purposes.
     *
     * @return string
     */
    public function getTimeslot()
    {
        return __($this->timeslot()->first()->timeslot_text);
    }

    /**
     * Used for display purposes.
     *
     * @return string
     */
    public function getResourcePerson()
    {
        return __($this->resourcePerson()->first()->person_label);
    }

    /**
     * Used for display purposes.
     *
     * @return string
     */
    public function getResourceMaterial()
    {
        $label = $this->resourceMaterial()->first();

        return ($label) ? __($label->rm_label) : __('activity.none');
    }

    /**
     * Used for display purposes.
     *
     * @return string
     */
    public function getLearningGoal()
    {
        return __($this->learningGoal()->first()->learninggoal_label);
    }

    public function getCompetencies()
    {
        return $this->competence()->first();
    }

    public function workplaceLearningPeriod()
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['learningGoal', 'competence', 'timeslot', 'resourcePerson', 'resourceMaterial'];
    }
}

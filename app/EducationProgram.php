<?php

declare(strict_types=1);
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\EducationProgram.
 *
 * @property EducationProgramType                                           $educationprogramType
 * @property int                                                            $ep_id
 * @property int                                                            $eptype_id
 * @property string                                                         $ep_name
 * @property int                                                            $disabled
 * @property \Illuminate\Database\Eloquent\Collection|\App\Category[]       $category
 * @property \Illuminate\Database\Eloquent\Collection|\App\Cohort[]         $cohorts
 * @property \Illuminate\Database\Eloquent\Collection|\App\Competence[]     $competence
 * @property \App\CompetenceDescription                                     $competenceDescription
 * @property \Illuminate\Database\Eloquent\Collection|\App\ResourcePerson[] $resourcePerson
 * @property \Illuminate\Database\Eloquent\Collection|\App\Student[]        $student
 * @property \Illuminate\Database\Eloquent\Collection|\App\Timeslot[]       $timeslot
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram whereEpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram whereEpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram whereEptypeId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgram query()
 */
class EducationProgram extends Model
{
    // Override the table used for the User Model
    protected $table = 'educationprogram';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'ep_id';

    // Default
    protected $fillable = [
        'ep_name', 'eptype_id',
    ];

    protected $casts = [
        'eptype_id' => 'int',
    ];

    public function competenceDescription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CompetenceDescription::class, 'education_program_id', 'ep_id');
    }

    public function category()
    {
        return $this->hasMany(Category::class, 'ep_id', 'ep_id');
    }

    public function educationprogramType(): BelongsTo
    {
        return $this->belongsTo(EducationProgramType::class, 'eptype_id', 'eptype_id');
    }

    public function student()
    {
        return $this->belongsToMany(\App\Student::class, 'ep_id', 'ep_id');
    }

    public function competence()
    {
        return $this->hasMany(\App\Competence::class, 'educationprogram_id', 'ep_id');
    }

    public function timeslot()
    {
        return $this->hasMany(\App\Timeslot::class, 'edprog_id', 'ep_id');
    }

    public function resourcePerson()
    {
        return $this->hasMany(\App\ResourcePerson::class, 'ep_id', 'ep_id');
    }

    public function getCompetencies()
    {
        return $this->competence()->get();
    }

    /**
     * @return Collection
     */
    public function getTimeslots()
    {
        return $this->timeslot()
            ->where('wplp_id', '=', 0)->get();
    }

    /**
     * @return Collection
     */
    public function getResourcePersons()
    {
        return $this->resourcePerson()
            ->where('ep_id', '=', $this->ep_id)
            ->where('wplp_id', '=', '0')
            ->get();
    }

    public function cohorts()
    {
        return $this->hasMany(Cohort::class, 'ep_id', 'ep_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['competenceDescription', 'category', 'educationprogramType', 'student',
                'competence', 'timeslot', 'resourcePerson', ];
    }
}

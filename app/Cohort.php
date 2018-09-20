<?php

namespace App;

use App\Tips\Models\Tip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Cohort.
 *
 * @property int                                                                     $id
 * @property string                                                                  $name
 * @property Tip[]|Collection                                                        $tips
 * @property Category[]|Collection                                                   $categories
 * @property Competence[]|Collection                                                 $competencies
 * @property EducationProgram                                                        $educationProgram
 * @property bool                                                                    $disabled
 * @property ResourcePerson[]|Collection                                             $resourcePersons
 * @property Timeslot[]|Collection                                                   $timeslots
 * @property string                                                                  $description
 * @property int                                                                     $ep_id
 * @property CompetenceDescription                                                   $competenceDescription
 * @property \Illuminate\Database\Eloquent\Collection|\App\WorkplaceLearningPeriod[] $workplaceLearningPeriods
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cohort whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cohort whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cohort whereEpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cohort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cohort whereName($value)
 * @mixin \Eloquent
 */
class Cohort extends Model
{
    protected $fillable = ['name', 'description', 'ep_id'];
    public $timestamps = false;

    public function categories(): HasMany
    {
        return tap($this->hasMany(Category::class, 'cohort_id', 'id'))->where('wplp_id', '0');
    }

    public function competencies(): HasMany
    {
        return $this->hasMany(Competence::class, 'cohort_id', 'id');
    }

    public function competenceDescription(): HasOne
    {
        return $this->hasOne(CompetenceDescription::class, 'cohort_id', 'id');
    }

    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'ep_id', 'ep_id');
    }

    public function resourcePersons(): HasMany
    {
        // Tap so we can apply the where clause but still return the relationship
        return tap($this->hasMany(ResourcePerson::class, 'cohort_id', 'id'))->where('wplp_id', '0');
    }

    public function timeslots(): HasMany
    {
        return $this->hasMany(Timeslot::class, 'cohort_id', 'id');
    }

    public function workplaceLearningPeriods(): HasMany
    {
        return $this->hasMany(WorkplaceLearningPeriod::class, 'cohort_id', 'id');
    }

    public function tips(): BelongsToMany
    {
        return $this->belongsToMany(Tip::class);
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['categories', 'competencies', 'competenceDescription', 'educationProgram', 'resourcePersons', 'timeslots', 'workplaceLearningPeriods'];
    }
}

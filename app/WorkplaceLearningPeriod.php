<?php
/**
 * This file (WorkplaceLearningPeriod.php) was created on 20/01/2017 at 12:32.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int    $wplp_id
 * @property Cohort $cohort
 * @property Category[]|Collection categories
 * @property LearningGoal[]|Collection learningGoals
 * @property LearningActivityActing[]|Collection learningActivityActing
 * @property int        $student_id
 * @property Student    $student
 * @property int        $wp_id
 * @property \DateTime  $startdate
 * @property \DateTime  $enddate
 * @property int        $nrofdays
 * @property string     $description
 * @property int        $cohort_id
 * @property float      $hours_per_day
 * @property Collection $chains
 * @property Workplace  $workplace
 */
class WorkplaceLearningPeriod extends Model
{
    // Override the table used for the User Model
    protected $table = 'workplacelearningperiod';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'wplp_id';

    protected $dates = [
        'startdate',
        'enddate',
    ];

    // Default
    protected $fillable = [
        'wplp_id',
        'student_id',
        'wp_id',
        'startdate',
        'enddate',
        'nrofdays',
        'description',
        'cohort_id',
        'hours_per_day',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function workplace(): HasOne
    {
        return $this->hasOne(Workplace::class, 'wp_id', 'wp_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'wplp_id', 'wplp_id');
    }

    public function learningGoals(): HasMany
    {
        return $this->hasMany(LearningGoal::class, 'wplp_id', 'wplp_id');
    }

    public function resourcePerson(): HasMany
    {
        return $this->hasMany(ResourcePerson::class, 'wplp_id', 'wplp_id');
    }

    public function timeslot(): HasMany
    {
        return $this->hasMany(Timeslot::class, 'wplp_id', 'wplp_id');
    }

    public function resourceMaterial(): HasMany
    {
        return $this->hasMany(ResourceMaterial::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityProducing(): HasMany
    {
        return $this->hasMany(LearningActivityProducing::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityActing(): HasMany
    {
        return $this->hasMany(LearningActivityActing::class, 'wplp_id', 'wplp_id');
    }

    public function getLearningActivityActingById($id)
    {
        return $this->learningActivityActing()
            ->where('laa_id', '=', $id)
            ->first();
    }

    public function getLearningActivityProducingById($id)
    {
        return $this->learningActivityProducing()
            ->where('lap_id', '=', $id)
            ->first();
    }

    public function getUnfinishedActivityProducing(): Collection
    {
        return $this->learningActivityProducing()
            ->where('status_id', '=', '2')
            ->orderBy('date', 'asc')
            ->orderBy('lap_id', 'desc')
            ->get();
    }

    public function getLearningGoals(): Collection
    {
        return $this->learningGoals()
            ->orderBy('learninggoal_id', 'asc')
            ->get();
    }

    public function hasLoggedHours(): bool
    {
        return \count($this->getLastActivity(1)) > 0;
    }

    public function getNumLoggedHours()
    {
        return $this->getLastActivity(1000000, 0)->sum('duration');
    }

    public function getResourcePersons(): Collection
    {
        return $this->resourcePerson()
            ->orderBy('rp_id', 'asc')
            ->get();
    }

    public function getTimeslots(): Collection
    {
        return $this->timeslot()->orderBy('timeslot_id', 'asc')->get();
    }

    public function getResourceMaterials(): Collection
    {
        return $this->resourceMaterial()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('rm_id', 'asc')
            ->get();
    }

    public function getLastActivity($count, $offset = 0)
    {
        /** @var Student $student */
        $student = $this->student;
        switch ($student->educationProgram->eptype_id) {
            case 2:
                return $this->getLastActivityProducing($count, $offset);
                break;
            case 1:
                return $this->getLastActivityActing($count, $offset);
                break;
            default:
                return collect([]);
        }
    }

    private function getLastActivityProducing($count, $offset = 0)
    {
        return $this->learningActivityProducing()
            ->orderBy('date', 'desc')
            ->orderBy('lap_id', 'desc')
            ->skip($offset)
            ->take($count)
            ->get();
    }

    private function getLastActivityActing($count, $offset = 0)
    {
        return $this->learningActivityActing()
            ->orderBy('date', 'desc')
            ->orderBy('laa_id', 'desc')
            ->skip($offset)
            ->take($count)
            ->get();
    }

    public function chains(): HasMany
    {
        return $this->hasMany(Chain::class, 'wplp_id', 'wplp_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return [
            'cohort',
            'student',
            'workplace',
            'categories',
            'learningGoals',
            'resourcePerson',
            'timeslot',
            'resourceMaterial',
            'learningActivityProducing',
            'learningActivityActing',
        ];
    }
}

<?php

declare(strict_types=1);
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

/**
 * App\WorkplaceLearningPeriod.
 *
 * @property int                                                                       $wplp_id
 * @property Cohort                                                                    $cohort
 * @property int                                                                       $student_id
 * @property Student                                                                   $student
 * @property int                                                                       $wp_id
 * @property \DateTime                                                                 $startdate
 * @property \DateTime                                                                 $enddate
 * @property int                                                                       $nrofdays
 * @property string                                                                    $description
 * @property int                                                                       $cohort_id
 * @property float                                                                     $hours_per_day
 * @property Collection                                                                $chains
 * @property Workplace                                                                 $workplace
 * @property int                                                                       $is_in_analytics
 * @property \Illuminate\Database\Eloquent\Collection|\App\Category[]                  $categories
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningActivityActing[]    $learningActivityActing
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningActivityProducing[] $learningActivityProducing
 * @property \Illuminate\Database\Eloquent\Collection|\App\LearningGoal[]              $learningGoals
 * @property \Illuminate\Database\Eloquent\Collection|\App\ResourceMaterial[]          $resourceMaterial
 * @property \Illuminate\Database\Eloquent\Collection|\App\ResourcePerson[]            $resourcePerson
 * @property \Illuminate\Database\Eloquent\Collection|\App\Timeslot[]                  $timeslot
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereEnddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereHoursPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereIsInAnalytics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereNrofdays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereWpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod whereWplpId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WorkplaceLearningPeriod query()
 */
class WorkplaceLearningPeriod extends Model
{
    // Override the table used for the User Model
    public $timestamps = false;

    // Disable using created_at and updated_at columns

    protected $table = 'workplacelearningperiod';

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
        'teacher_id',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'teacher_id', 'student_id');
    }

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class, 'wp_id', 'wp_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'wplp_id', 'wplp_id');
    }

    public function learningGoals(): HasMany
    {
        return $this->hasMany(LearningGoal::class, 'wplp_id', 'wplp_id');
    }

    public function getLearningActivityActingById($id)
    {
        return $this->learningActivityActing()
            ->where('laa_id', '=', $id)
            ->first();
    }

    public function learningActivityActing(): HasMany
    {
        return $this->hasMany(LearningActivityActing::class, 'wplp_id', 'wplp_id');
    }

    public function getLearningActivityProducingById($id)
    {
        return $this->learningActivityProducing()
            ->where('lap_id', '=', $id)
            ->first();
    }

    public function learningActivityProducing(): HasMany
    {
        return $this->hasMany(LearningActivityProducing::class, 'wplp_id', 'wplp_id');
    }

    public function getUnfinishedActivityProducing(): Collection
    {
        return $this->learningActivityProducing()
            ->where('status_id', '=', '2')
            ->orderBy('date', 'asc')
            ->orderBy('lap_id', 'desc')
            ->get();
    }

    public function hasLoggedHours(): bool
    {
        return \count($this->getLastActivity(1)) > 0;
    }

    public function getLastActivity($count, $offset = 0)
    {
        switch ($this->student->educationProgram->eptype_id) {
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

    public function resourcePerson(): HasMany
    {
        return $this->hasMany(ResourcePerson::class, 'wplp_id', 'wplp_id');
    }

    public function getTimeslots(): Collection
    {
        return $this->timeslot()->orderBy('timeslot_id', 'asc')->get();
    }

    public function timeslot(): HasMany
    {
        return $this->hasMany(Timeslot::class, 'wplp_id', 'wplp_id');
    }

    public function getResourceMaterials(): Collection
    {
        return $this->resourceMaterial()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('rm_id', 'asc')
            ->get();
    }

    public function resourceMaterial(): HasMany
    {
        return $this->hasMany(ResourceMaterial::class, 'wplp_id', 'wplp_id');
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

    /**
     * Calculates the effective hours worked for producing activities.
     */
    public function getEffectiveDays(): int
    {
        $activities = $this->learningActivityProducing->all();
        $daysWithHours = array_reduce($activities, static function (array $carry, LearningActivityProducing $activity) {
            // Timestamp is okay for string representation because DB field type is "date" and has no H:i:s indication
            if (!isset($carry[$activity->date->timestamp])) {
                $carry[$activity->date->timestamp] = 0;
            }

            $carry[$activity->date->timestamp] += $activity->duration;

            return $carry;
        }, []);

        $daysWithHours = array_filter($daysWithHours, function (int $dayTimestamp, float $hours): bool {
            return $hours > ($this->hours_per_day - 1);
        }, ARRAY_FILTER_USE_BOTH);

        array_walk($daysWithHours, function (float &$hours, int $dayTimestamp) {
            if ($hours > ($this->hours_per_day + 1)) {
                $hours = $this->hours_per_day + 1;
            }
        });

        $totalHours = array_sum(array_values($daysWithHours));
        $daysRegistered = count(array_keys($daysWithHours));

        return (int) min($daysRegistered, floor($totalHours / $this->hours_per_day));
    }

    public function hasActivities(): bool
    {
        return $this->learningActivityActing()->count() > 0 || $this->learningActivityProducing()->count() > 0;
    }
}

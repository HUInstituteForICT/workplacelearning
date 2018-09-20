<?php

namespace App;

use App\Interfaces\LearningActivityInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\LearningActivityProducing.
 *
 * @property int                                 $lap_id
 * @property int                                 $chain_id
 * @property string                              $description
 * @property Chain                               $chain
 * @property int                                 $wplp_id
 * @property WorkplaceLearningPeriod             $workplaceLearningPeriod
 * @property float                               $duration
 * @property string                              $res_material_id
 * @property string                              $res_material_detail
 * @property Category                            $category
 * @property Difficulty                          $difficulty
 * @property Status                              $status
 * @property ResourceMaterial                    $resourceMaterial
 * @property ResourcePerson                      $resourcePerson
 * @property int                                 $status_id
 * @property Carbon                              $date
 * @property Feedback                            $feedback
 * @property int|null                            $prev_lap_id
 * @property int|null                            $res_person_id
 * @property int                                 $category_id
 * @property int                                 $difficulty_id
 * @property \App\LearningActivityProducing      $nextLearningActivityProducing
 * @property \App\LearningActivityProducing|null $previousLearningActivityProducing
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereDifficultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereLapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing wherePrevLapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereResMaterialDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereResMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereResPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LearningActivityProducing whereWplpId($value)
 * @mixin \Eloquent
 */
class LearningActivityProducing extends Model implements LearningActivityInterface
{
    // Override the table used for the User Model
    protected $table = 'learningactivityproducing';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'lap_id';

    // Default
    protected $fillable = [
        'duration',
        'description',
        'date',
        'prev_lap_id',
        'res_person_id',
        'res_material_id',
        'res_material_detail',
        'category_id',
        'difficulty_id',
        'status_id',
    ];

    public function previousLearningActivityProducing(): BelongsTo
    {
        return $this->belongsTo(LearningActivityProducing::class, 'prev_lap_id', 'lap_id');
    }

    public function nextLearningActivityProducing(): BelongsTo
    {
        return $this->belongsTo(LearningActivityProducing::class, 'lap_id', 'prev_lap_id');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'learningactivity_id');
    }

    public function resourcePerson(): BelongsTo
    {
        return $this->belongsTo(ResourcePerson::class, 'res_person_id');
    }

    public function resourceMaterial(): BelongsTo
    {
        return $this->belongsTo(ResourceMaterial::class, 'res_material_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id', 'difficulty_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['previousLearningActivityProducing',
                'nextLearningActivityProducing',
                'workplaceLearningPeriod',
                'feedback',
                'resourcePerson',
                'resourceMaterial',
                'category',
                'difficulty',
                'status',
                ];
    }

    // Note: DND, object comparison
    public function __toString()
    {
        return $this->lap_id.'';
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id', 'id');
    }
}

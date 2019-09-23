<?php
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
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
 * App\Category.
 *
 * @property int                            $category_id
 * @property string                         $category_label
 * @property int                            $wplp_id
 * @property int|null                       $ep_id
 * @property int|null                       $cohort_id
 * @property \App\WorkplaceLearningPeriod   $InternshipPeriods
 * @property \App\Cohort|null               $cohort
 * @property \App\EducationProgram|null     $educationProgram
 * @property \App\LearningActivityProducing $learningactivitiesproducing
 * @property \App\WorkplaceLearningPeriod   $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCategoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereEpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereWplpId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category query()
 */
class Category extends Model implements HasLabelProperty, IsTranslatable
{
    use TranslatableEntity;

    // Override the table used for the User Model
    protected $table = 'category';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'category_id';

    // Default
    protected $fillable = [
        'category_id',
        'category_label',
        'wplp_id',
        'cohort_id',
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, 'ep_id', 'ep_id');
    }

    public function InternshipPeriods()
    {
        return $this->belongsTo(\App\WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function learningactivitiesproducing()
    {
        return $this->belongsTo(\App\LearningActivityProducing::class, 'category_id', 'category_id');
    }

    public function getCategoryLabel()
    {
        return $this->category_label;
    }

    public function setCategoryLabel($label): void
    {
        $this->category_label = $label;
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['cohort', 'educationProgram', 'InternshipPeriods', 'learningactivitiesproducing'];
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id');
    }

    public function getLabel(): string
    {
        return $this->category_label ?? 'Unknown category';
    }
}

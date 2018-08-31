<?php
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $category_label
 * @property int    $wplp_id
 */
class Category extends Model
{
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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\CompetenceDescription.
 *
 * @property int                        $id
 * @property int|null                   $education_program_id
 * @property int|null                   $cohort_id
 * @property \App\Cohort|null           $cohort
 * @property \App\EducationProgram|null $educationProgram
 * @property string                     $download_url
 * @property string                     $file_name
 * @property bool                       $has_data
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription whereEducationProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription whereId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CompetenceDescription query()
 */
class CompetenceDescription extends Model
{
    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id', 'ep_id');
    }

    public $timestamps = false;

    protected $appends = ['has_data', 'download-url'];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    /**
     * @return bool whether this description exists and has data
     */
    public function getHasDataAttribute()
    {
        return Storage::disk('local')->exists($this->file_name);
    }

    /**
     * @return string the url to follow to download this description
     */
    public function getDownloadUrlAttribute()
    {
        return route('competence-description', ['id' => $this->id]);
    }

    /**
     * @return string the unique file name for this description
     */
    public function getFileNameAttribute()
    {
        return "competence-descriptions/competence-description-{$this->id}.pdf";
    }
}

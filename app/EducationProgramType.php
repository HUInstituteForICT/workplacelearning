<?php

declare(strict_types=1);
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\EducationProgramType.
 *
 * @property int                                                              $eptype_id
 * @property string                                                           $eptype_name       Name of the program type
 * @property \Illuminate\Database\Eloquent\Collection|\App\EducationProgram[] $educationPrograms
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgramType whereEptypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgramType whereEptypeName($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgramType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgramType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EducationProgramType query()
 */
class EducationProgramType extends Model
{
    // Override the table used for the User Model
    protected $table = 'educationprogramtype';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'eptype_id';

    // Default
    protected $fillable = [
        'eptype_id',
        'eptype_name',
    ];

    public function educationPrograms(): HasMany
    {
        return $this->hasMany(EducationProgram::class);
    }

    public function isActing()
    {
        return \in_array(strtolower($this->eptype_name), ['acting']);
    }

    public function isProducing()
    {
        return \in_array(strtolower($this->eptype_name), ['producing']);
    }
}

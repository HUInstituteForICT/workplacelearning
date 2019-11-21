<?php

declare(strict_types=1);
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Workplace.
 *
 * @property int                                                                $wp_id
 * @property string                                                             $wp_name
 * @property string                                                             $street
 * @property string                                                             $housenr
 * @property string                                                             $postalcode
 * @property string                                                             $town
 * @property string                                                             $country
 * @property string                                                             $contact_name
 * @property string                                                             $contact_email
 * @property string                                                             $contact_phone
 * @property int                                                                $numberofemployees
 * @property \Illuminate\Database\Eloquent\Collection|WorkplaceLearningPeriod[] $internshipperiod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereHousenr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereNumberofemployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace wherePostalcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereWpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace whereWpName($value)
 * @mixin \Eloquent
 *
 * @property \App\WorkplaceLearningPeriod $workplaceLearningPeriod
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Workplace query()
 */
class Workplace extends Model
{
    // Override the table used for the User Model
    protected $table = 'workplace';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'wp_id';

    // Default
    protected $fillable = [
        'wp_id',
        'wp_name',
        'street',
        'housenr',
        'postalcode',
        'town',
        'country',
        'contact_name',
        'contact_email',
        'contact_phone',
        'numberofemployees',
    ];

    public function internshipperiod(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkplaceLearningPeriod::class, 'wp_id', 'wp_id');
    }

    public function workplaceLearningPeriod(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WorkplaceLearningPeriod::class, 'wp_id', 'wp_id');
    }
}

<?php
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\InternshipPeriod;

/**
 * Class Workplace
 * @package App
 *
 * @property int $wp_id
 * @property string $wp_name
 * @property string $street
 * @property string $housenr
 * @property string $postalcode
 * @property string $town
 * @property string $country
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property int $numberofemployees
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


    public function internshipperiod()
    {
        return $this->hasMany(\App\WorkplaceLearningPeriod::class, 'wp_id', 'wp_id');
    }
}

<?php
/**
 * This file (Werkzaamheid.php) was created on 06/06/2016 at 17:55.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Internship;
use App\InternshipPeriod;

class Werkzaamheid extends Model{
    // Override the table used for the User Model
    protected $table = 'werkzaamheden';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'wzh_id';

    // Default
    protected $fillable = [
        'student_stage_id',
        'wzh_aantaluren',
        'wzh_omschrijving',
        'wzh_datum',
        'lerenmet',
        'lerenmetdetail',
        'moeilijkheid_id',
        'status_id',
        'categorie_id',
        'prev_wzh_id',
        'display',
        'created_at',
    ];

    public function internshipperiods(){
        return $this->belongsTo('App\InternshipPeriod', 'stud_stid', 'student_stage_id');
    }

    public function feedback(){
        return $this->hasOne('App\Feedback');
    }

    public function getStartMonthNumber(){
        return date("m", strtotime($this->startdatum));
    }

    public function getEndMonthNumber(){
        return date('m', strtotime($this->einddatum));
    }

    public function getNextWerkzaamheid(){
        return Werkzaamheid::where('prev_wzh_id', $this->wzh_id)->first();
    }

    public function getPreviousWerkzaamheid(){
        return Werkzaamheid::where('wzh_id', $this->prev_wzh_id)->first();
    }

    public function getFeedback(){
        return Feedback::where('wzh_id', $this->wzh_id)->first();
    }

    public function getlerenmetdetail(){
        $dtl = null;
        switch($this->lerenmet){
            case "persoon" :
                $dtl = DB::table("samenwerkingsverbanden")->where('swv_id', $this->lerenmetdetail)->first()->swv_value;
            break;
            case "alleen" :
            case "internet" :
            case "boek" :
            default :
                $dtl = $this->lerenmetdetail;
            break;
        }
        return $dtl;
    }

    public function getAantalUrenString(){
        switch($this->wzh_aantaluren){
            case 0.25: return "15 min.";
            case 0.5 : return "30 min.";
            case 0.75: return "45 min.";
            default: return $this->wzh_aantaluren." uur";
        }
    }

    public function getMoeilijkheid(){
        $mh = DB::table("moeilijkheden")->where('mh_id', $this->moeilijkheid_id)->first();
        return $mh->mh_value;
    }

    public function getStatus(){
        $st = DB::table("statussen")->where('st_id', $this->status_id)->first();
        return $st->st_value;
    }

    public function getCategorie(){
        $cg = DB::table("categorieen")->where('cg_id', $this->categorie_id)->first();
        return $cg->cg_value;
    }

    // Note: DND, object comparison
    public function __toString() {
        return $this->wzh_id;
    }
}
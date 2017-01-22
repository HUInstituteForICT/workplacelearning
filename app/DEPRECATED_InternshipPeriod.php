<?php
/**
 * This file (InternshipPeriod.php) was created on 06/06/2016 at 15:53.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternshipPeriod extends Model{
    // Override the table used for the User Model
    protected $table = 'student_stages';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'stud_stid';

    // Default
    protected $fillable = [
        'stud_stid',
        'student_id',
        'stage_id',
        'startdatum',
        'einddatum',
        'aantaluren',
        'opdrachtomschrijving',
    ];

    public function getID(){
        return $this->stud_stid;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function internship(){
        return $this->hasOne('App\Internship', 'stp_id', 'stageplaats_id');
    }

    public function werkzaamheden(){
        return $this->hasMany('App\Werkzaamheid', 'student_stage_id', 'stud_stid');
    }

    public function samenwerkingsverbanden(){
        return $this->hasMany('App\Samenwerkingsverband', 'ss_id', 'stud_stid');
    }

    public function categorieen(){
        return $this->hasMany('App\Categorie', 'ss_id', 'stud_stid');
    }

    public function deadlines(){
        return $this->hasMany('App\Deadline', 'stud_stage_id', 'stud_stid');
    }

    public function getInternship(){
        $is = null;
        try{
            $is = $this->internship()->first();
        } catch(Exception $e){
            $is = new Internship;
        }
        return $is;
    }

    public function hasLoggedHours(){
        return (count($this->getLastWerkzaamheden(1)) > 0);
    }

    public function getNumLoggedHours(){
        return $this->werkzaamheden()->where('student_stage_id', '=', $this->stud_stid)->sum('wzh_aantaluren');
    }

    public function getAllWerkzaamheden(){
        return $this->werkzaamheden()->get();
    }

    public function getLastWerkzaamheden($count){
        return $this->werkzaamheden()->orderBy('wzh_datum', 'desc')->orderBy('wzh_id', 'desc')->limit($count)->get();
    }

    public function getWerkzaamheden($count, $page = 1){
        return $this->werkzaamheden()->skip(($count*$page)-$count)->take($count)->orderBy('wzh_datum', 'desc')->orderBy('wzh_id', 'desc')->get();
    }

    public function getUnfinishedWerkzaamheden(){
        return $this->werkzaamheden()->where('display', '=', '1')->where('status_id', '=', '2')->orderBy('wzh_datum', 'asc')->orderBy('wzh_id', 'desc')->get();
    }

    public function getAssignmentDescription(){
        return $this->opdrachtomschrijving;
    }

    public function getNumHours(){
        return $this->aantaluren;
    }

    public function getStartDate(){
        return $this->startdatum;
    }

    public function getEndDate(){
        return $this->einddatum;
    }

    public function getStartMonthNumber(){
        return date("m", strtotime($this->startdatum));
    }

    public function getEndMonthNumber(){
        return date('m', strtotime($this->einddatum));
    }


}
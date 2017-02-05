<?php
/**
 * This file (DEPRECATED_Chart.php) was created on 06/12/2016 at 17:58.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model{
    // Override the table used for the User Model
    protected $table = 'charts';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'id';

    // Default
    protected $fillable = [
        'id',
        'student_id',
        'title',
        'serializedData',
        'date',
    ];

    public function setDataset($array = array()){
        $this->serializedData = serialize($array);
    }

    public function getDataset(){
        return unserialize($this->serializedData);
    }

    public function getSerializedDataset(){
        return $this->serializedData;
    }

    public function setSerializedDataset($string){
        $this->serializedData = $string;
    }
    
    public function setDate($datestring){
        $this->date = date('Y-m-d', strtotime($datestring));
    }
    
    public function getDate(){
        return $this->date;
    }
}
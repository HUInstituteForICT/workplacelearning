<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Saved items
@stop
@section('content')
<?php
use App\Student;
use App\SavedLearningItems
/** @var Student $student */
/** @var SavedLearningItems $sli */?>

    <div class="container-fluid">
        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-3">


                @card
                    <h1>Bewaard</h1>
                    <h4>{{ $sli }}</h4>
                @endcard

            </div>

         </div>
    </div>
@stop

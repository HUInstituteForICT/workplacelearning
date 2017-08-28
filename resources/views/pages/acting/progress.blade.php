<?php
/**
 * This file (progress.blade.php) was created on 06/29/2016 at 12:19.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Voortgang
@stop
@section('content')
    <div class="container-fluid">
        <!-- Internship Info -->
        <div class="row">
            <div class="col-md-11">
                <h3>Leermomenten</h3>
            </div>
        </div>
        <div clas="row">
        <table class="table blockTable col-md-12">
            <thead class="blue_tile">
                <tr>
                    <td>Datum</td>
                    <td>Situatie</td>
                    <td>Categorie</td>
                    <td>Wat heb je geleerd?</td>
                    <td>Leervraag</td>
                    <td>Competentie</td>
                </tr>
            </thead>
            @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(15, ($page*15)-15) as $a)
                    <tr>
                        <td>{{ date('d-m', strtotime($a->date)) }}</td>
                        <td>{{ $a->situation }}</td>
                        <td>{{ \App\Timeslot::find($a->timeslot_id)->timeslot_text }}</td>
                        <td>{{ $a->lessonslearned }}</td>
                        <td>{{ $a->getLearningGoal() }}</td>
                        <td>{{ $a->getCompetencies()->competence_label }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        </div>
        @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
            <div class="row">
                <div class="col-md-6" style="text-align:left;">
                    @if($page>1)
                        <a href="{{ route('progress-producing', ['page' => ($page-1)]) }}">Previous Page</a>
                    @endif
                </div>
                <div class="col-md-6" style="text-align:right;">
                    @if(count(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(15, ($page*15))) > 0)
                        <a href="{{ route('progress-producing', ['page' => ($page+1)]) }}">Next Page</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@stop

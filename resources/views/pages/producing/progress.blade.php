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
        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() == NULL)
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-notice">
                        <span>{{ Lang::get('elements.alerts.notice') }}: </span>{!! str_replace('%s', LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "stageperiode/edit/0", array()), Lang::get('dashboard.nointernshipactive')) !!}
                    </div>
                </div>
            </div>
        @endif
        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif
        <!-- Internship Info -->
        <div class="row">
            <div class="col-md-11">
                <h3>Weekstaten</h3>
            </div>
            <div class="col-md-1">
                <a class="btn btn-info" role="button" target="_blank" href="{{ route('report-producing-export') }}">Export</a>
            </div>
        </div>
        <div clas="row">
        <table class="table blockTable col-md-12">
            <thead class="blue_tile">
                <tr>
                    <td>Datum</td>
                    <td>Omschrijving</td>
                    <td>Tijd (Uren)</td>
                    <td>Werken/leren met</td>
                    <td>Complexiteit</td>
                </tr>
            </thead>
            @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(15, ($page*15)-15)->get() as $a)
                <tr>
                    <td>{{ date('d-m', strtotime($a->date)) }}</td>
                    <td>{{ $a->description }}</td>
                    <td>{{ $a->getDurationString() }}</td>
                    <td>{{ ucwords($a->description) . (($a->getResourceDetail() != null) ? ": ".$a->getResourceDetail() : "") }}</td>
                    <td>{{ $a->getDifficulty() }}</td>
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
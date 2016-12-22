<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
   Profiel: {{ Auth::user()->voornaam ." ". Auth::user()->achternaam }}
@stop
@section('content')
    <div class="container-fluid">
        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-6">
                {!! Form::open(array('url' => URL::to('profiel/update', array(), true), 'class' => 'form-horizontal well')) !!}
                {!! Form::hidden('stud_id', Auth::user()->stud_id) !!}
                <h2>{{ Lang::get('elements.profile.title') }}</h2>
                <div class="form-group">
                    {!! Form::label('studentnr', Lang::get('elements.profile.labels.studentnr'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6"><p class="form-control-static"><b>{{ Auth::user()->studentnummer }}</b></p></div>
                </div>
                <div class="form-group">
                    {!! Form::label('firstname', Lang::get('elements.profile.labels.firstname'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6">{!! Form::text('firstname', Auth::user()->voornaam, array('placeholder' => Lang::get('elements.profile.placeholders.firstname'), 'class' => 'form-control')) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('lastname', Lang::get('elements.profile.labels.lastname'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6">{!! Form::text('lastname', Auth::user()->achternaam, array('placeholder' => Lang::get('elements.profile.placeholders.lastname'), 'class' => 'form-control')) !!}</div>
                </div>
                <!-- <div class="form-group">
                    {!! Form::label('birthdate', Lang::get('elements.profile.labels.birthdate'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->geboortedatum)) }}</p></div>
                </div> -->
                <div class="form-group">
                    {!! Form::label('email', Lang::get('elements.profile.labels.email'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6">{!! Form::text('email', Auth::user()->email, array('placeholder' => Lang::get('elements.profile.placeholders.email'), 'class' => 'form-control')) !!}</p></div>
                    <div class="col-sm-2"><input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" /></div>
                </div>
                <!-- <div class="form-group">
                    {!! Form::label('phone', Lang::get('elements.profile.labels.phone'), array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-4">{!! Form::text('phone', Auth::user()->telefoon, array('placeholder' => Lang::get('elements.profile.placeholders.phone'), 'class' => 'form-control')) !!}</div>
                </div> -->
                {!! Form::close() !!}
            </div>
            @if(Auth::user()->getCurrentInternship())
                <!-- Current Internship -->
                <div class="col-md-6">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal well')) !!}
                    <h2>{{ Lang::get('elements.profile.internships.current.title') }}</h2>
                    <div class="form-group">
                        {!! Form::label('companyname', Lang::get('elements.profile.internships.companyname'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentInternship()->bedrijfsnaam ." (".Auth::user()->getCurrentInternship()->plaats.")" }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', Lang::get('elements.profile.internships.contactperson'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentInternship()->contactpersoon }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', Lang::get('elements.profile.internships.contactphone'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentInternship()->telefoon }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', Lang::get('elements.profile.internships.contactemail'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentInternship()->contactemail }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', Lang::get('elements.profile.internships.startdate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->getCurrentInternshipPeriod()->startdatum)) }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', Lang::get('elements.profile.internships.enddate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->getCurrentInternshipPeriod()->einddatum)) }}</p></div>
                    </div>
                    {!! Form::close() !!}
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-11">
                <h3>{{ Lang::get('elements.profile.internships.profile.title') }}</h3>
            </div>
            <div class="col-md-1">
                <a href="{{ url('/stageperiode/edit/0') }}"><img style="float:right; margin-top:20px;" class="table-icon" src="{{ secure_asset('assets/img/icn-new.svg') }}" /></a>
            </div>
            <table class="table blockTable col-md-12">
                <thead class="blue_tile">
                    <tr>
                        <th></th>
                        <th>{{ Lang::get('elements.profile.internships.companyname') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.startdate') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.enddate') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.companylocation') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.contactperson') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.contactemail') }}</th>
                        <th>{{ Lang::get('elements.profile.internships.contactphone') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach(Auth::user()->getInternshipPeriods() as $is)
                    <tr class="{{ (Auth::user()->getCurrentInternshipPeriod() && Auth::user()->getCurrentInternshipPeriod()->stud_stid == $is->stud_stid) ? "highlight" : "" }}">
                        <td><a href="{{ LaravelLocalization::GetLocalizedURL(null, '/stageperiode/edit/'.$is->stud_stid, array()) }}"><img class="table-icon" src="{{ secure_asset("assets/img/icn-edit.svg") }}" /></td></a>
                        <td>{{ $is->bedrijfsnaam }}</td>
                        <td>{{ date('d-m-Y', strtotime($is->startdatum)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($is->einddatum)) }}</td>
                        <td>{{ $is->plaats }}</td>
                        <td>{{ $is->contactpersoon }}</td>
                        <td>{{ $is->contactemail }}</td>
                        <td>{{ $is->telefoon }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
         </div>
    </div>
@stop

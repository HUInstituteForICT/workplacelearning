<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
   {{ Lang::get('home.profile') }}: {{ Auth::user()->firstname ." ". Auth::user()->lastname }}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-3">

                {!! Form::open(array('url' => URL::to('profiel/update'), 'class' => 'form-horizontal well')) !!}
                {!! Form::hidden('student_id', Auth::user()->student_id) !!}
                <h2>{{ Lang::get('elements.profile.title') }}</h2>
                <div class="form-group">
                    {!! Form::label('studentnr', Lang::get('elements.profile.labels.studentnr'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6"><p class="form-control-static"><b>&nbsp;{{ Auth::user()->studentnr }}</b></p></div>
                </div>
                <div class="form-group">
                    {!! Form::label('firstname', Lang::get('elements.profile.labels.firstname'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6">{!! Form::text('firstname', Auth::user()->firstname, array('placeholder' => Lang::get('elements.profile.placeholders.firstname'), 'class' => 'form-control')) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('lastname', Lang::get('elements.profile.labels.lastname'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6">{!! Form::text('lastname', Auth::user()->lastname, array('placeholder' => Lang::get('elements.profile.placeholders.lastname'), 'class' => 'form-control')) !!}</div>
                </div>
            <!-- <div class="form-group">
                    {!! Form::label('birthdate', Lang::get('elements.profile.labels.birthdate'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->birthdate)) }}</p></div>
                </div> -->
                <div class="form-group">
                    {!! Form::label('email', Lang::get('elements.profile.labels.email'), array('class' => 'col-sm-4 control-label')) !!}
                    {{--<div class="col-sm-6"><p class="form-control-static">{{ Auth::user()->email }}</p></div>--}}
                    <div class="col-sm-6">{!! Form::email('email', Auth::user()->email, array('placeholder' => Lang::get('elements.profile.placeholders.email'), 'class' => 'form-control')) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('language', Lang::get('elements.profile.labels.language'), ["class" => "col-sm-4 control-label"]) !!}
                    <div class="col-sm-6">{!! Form::select('locale', $locales, Auth::user()->locale, ["class" => "form-control"] )!!}</div>

                </div>
            <!-- <div class="form-group">
                    {!! Form::label('phone', Lang::get('elements.profile.labels.phone'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-4">{!! Form::text('phone', Auth::user()->phonenr, array('placeholder' => Lang::get('elements.profile.placeholders.email'), 'class' => 'form-control')) !!}</div>

                </div> -->
                <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                {!! Form::close() !!}
            </div>

            <div class="col-md-3">

                {!! Form::open(array('url' => URL::to('profiel/change-password'), 'method' => 'put', 'class' => 'form-horizontal well')) !!}
                <h2>{{ Lang::get('passwords.change') }}</h2>
                <div class="form-group">
                    {!! Form::label('current_password', Lang::get('elements.profile.labels.password'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6">{!! Form::password('current_password', ['class' => 'form-control']) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('new_password', Lang::get('elements.profile.labels.new_password'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6">{!! Form::password('new_password', ['class' => 'form-control']) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('confirm_password', Lang::get('elements.profile.labels.password_repeat'), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-6">{!! Form::password('confirm_password', ['class' => 'form-control']) !!}</div>
                </div>
                <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}"/>
                {!! Form::close() !!}
            </div>

            @if(Auth::user()->getCurrentWorkplace())
                <!-- Current Internship -->
                <div class="col-md-6">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal well')) !!}
                    <h2>{{ Lang::get('elements.profile.internships.current.title') }}</h2>
                    <div class="form-group">
                        {!! Form::label('companyname', Lang::get('elements.profile.internships.companyname'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentWorkplace()->wp_name ." (".Auth::user()->getCurrentWorkplace()->town.")" }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', Lang::get('elements.profile.internships.contactperson'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentWorkplace()->contact_name }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', Lang::get('elements.profile.internships.contactphone'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentWorkplace()->contact_phone }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', Lang::get('elements.profile.internships.contactemail'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ Auth::user()->getCurrentWorkplace()->contact_email }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', Lang::get('elements.profile.internships.startdate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate)) }}</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', Lang::get('elements.profile.internships.enddate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8"><p class="form-control-static">{{ date('d-m-Y', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->enddate)) }}</p></div>
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
                <a href="{{ url('/period/create') }}"><img style="float:right; margin-top:20px;" class="table-icon" src="{{ secure_asset('assets/img/icn-new.svg') }}" /></a>
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
                @foreach(Auth::user()->getWorkplaceLearningPeriods() as $wplp)
                    <tr class="{{ (Auth::user()->getCurrentWorkplaceLearningPeriod() && Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id == $wplp->wplp_id) ? "highlight" : "" }}">
                        <td><a href="{{ '/period/edit/'.$wplp->wplp_id }}"><img class="table-icon" src="{{ secure_asset("assets/img/icn-edit.svg") }}" /></a></td>
                        <td>{{ $wplp->workplace->wp_name }}</td>
                        <td>{{ date('d-m-Y', strtotime($wplp->startdate)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($wplp->enddate)) }}</td>
                        <td>{{ $wplp->workplace->town }}</td>
                        <td>{{ $wplp->workplace->contact_name }}</td>
                        <td>{{ $wplp->workplace->contact_email }}</td>
                        <td>{{ $wplp->workplace->contact_phone }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
         </div>
    </div>
@stop

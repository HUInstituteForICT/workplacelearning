<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('home.profile') }}: {{ Auth::user()->firstname ." ". Auth::user()->lastname }}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-3">


                @card
                {!! Form::open(array('url' => URL::to('profiel/update'))) !!}
                {!! Form::hidden('student_id', Auth::user()->student_id) !!}
                <h2>{{ __('elements.profile.title') }}</h2>
                <div class="form-group">
                    {!! Form::label('studentnr', __('elements.profile.labels.studentnr'), array('class' => 'control-label')) !!}
                    <p class="form-control-static"><b>&nbsp;{{ Auth::user()->studentnr }}</b></p>
                </div>
                <div class="form-group">
                    {!! Form::label('firstname', __('elements.profile.labels.firstname'), array('class' => 'control-label')) !!}
                    {!! Form::text('firstname', Auth::user()->firstname, array('placeholder' => __('elements.profile.placeholders.firstname'), 'class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('lastname', __('elements.profile.labels.lastname'), array('class' => 'control-label')) !!}
                    {!! Form::text('lastname', Auth::user()->lastname, array('placeholder' => __('elements.profile.placeholders.lastname'), 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', __('elements.profile.labels.email'), array('class' => 'control-label')) !!}
                    @if(Auth::user()->isRegisteredThroughCanvas())
                        <p class="form-control-static"><b>&nbsp;{{ Auth::user()->email }}</b></p>
                    @else
                        {!! Form::email('email', Auth::user()->email, array('placeholder' => __('elements.profile.placeholders.email'), 'class' => 'form-control')) !!}
                    @endif
                </div>
                <div class="form-group">
                    {!! Form::label('language', __('elements.profile.labels.language'), ['class' => 'control-label']) !!}
                    {!! Form::select('locale', $locales, Auth::user()->locale, ['class' => 'form-control'] )!!}
                </div>
                <input type="submit" class="btn btn-info btn-block"
                       value="{{ __('elements.profile.btnsave') }}"/>

                @if(Auth::user()->isCoupledToCanvasAccount() && !Auth::user()->isRegisteredThroughCanvas())
                    <hr>
                    <br/><br/><br/>
                    <a href="{{ route('uncouple-canvas') }}" class="btn btn-danger btn-block"
                       style="white-space: normal;">{{ __('Koppeling met Canvas verwijderen') }}</a>
                @endif
                {!! Form::close() !!}

                @endcard

            </div>

            @if(!Auth::user()->isRegisteredThroughCanvas())
                <div class="col-md-3">

                    @card
                    {!! Form::open(array('url' => URL::to('profiel/change-password'), 'method' => 'put')) !!}
                    <h2>{{ __('passwords.change') }}</h2>
                    <div class="form-group">
                        {!! Form::label('current_password', __('elements.profile.labels.password'), array('class' => 'control-label')) !!}
                        {!! Form::password('current_password', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password', __('elements.profile.labels.new_password'), array('class' => 'control-label')) !!}
                        {!! Form::password('new_password', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('confirm_password', __('elements.profile.labels.password_repeat'), array('class' => 'control-label')) !!}
                        {!! Form::password('confirm_password', ['class' => 'form-control']) !!}
                    </div>
                    <input type="submit" class="btn btn-info btn-block"
                           value="{{ __("elements.profile.btnsave") }}"/>
                    {!! Form::close() !!}

                    @endcard

                </div>
            @endif

            @if(Auth::user()->hasCurrentWorkplaceLearningPeriod())
            <!-- Current Internship -->
                <div class="col-md-3">
                    @card
                    {!! Form::open(array('url' => 'dummy')) !!}
                    <h2>{{ __('elements.profile.internships.current.title') }}</h2>
                    <div class="form-group">
                        {!! Form::label('companyname', __('elements.profile.internships.companyname'), array('class' => 'control-label')) !!}
                        <p>
                            {{ Auth::user()->getCurrentWorkplace()->wp_name .' ('.Auth::user()->getCurrentWorkplace()->town.")" }}
                        </p>

                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', __('elements.profile.internships.contactperson'), array('class' => 'control-label')) !!}
                        <p>
                            {{ Auth::user()->getCurrentWorkplace()->contact_name }}
                        </p>

                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', __('elements.profile.internships.contactphone'), array('class' => 'control-label')) !!}
                        <p>
                            {{ Auth::user()->getCurrentWorkplace()->contact_phone }}
                        </p>

                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', __('elements.profile.internships.contactemail'), array('class' => 'control-label')) !!}
                        <p>
                            {{ Auth::user()->getCurrentWorkplace()->contact_email }}
                        </p>

                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', __('elements.profile.internships.startdate'), array('class' => 'control-label')) !!}
                        <p>
                            {{ date('d-m-Y', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate)) }}
                        </p>

                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', __('elements.profile.internships.enddate'), array('class' => 'control-label')) !!}
                        <p>
                            {{ date('d-m-Y', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->enddate)) }}
                        </p>

                    </div>
                    {!! Form::close() !!}
                    @endcard
                </div>
            @endif
        </div>


        <div class="row">
            <div class="col-md-11">
                <h3>{{ __('elements.profile.internships.profile.title') }}</h3>
            </div>
            <div class="col-md-1">
                <a href="{{ url('/period/create') }}"><img style="float:right; margin-top:20px;" class="table-icon"
                                                           src="{{ secure_asset('assets/img/icn-new.svg') }}"/></a>
            </div>
            <table class="table blockTable col-md-12">
                <thead class="blue_tile">
                <tr>
                    <th></th>
                    <th>{{ __('elements.profile.internships.companyname') }}</th>
                    <th>{{ __('elements.profile.internships.startdate') }}</th>
                    <th>{{ __('elements.profile.internships.enddate') }}</th>
                    <th>{{ __('elements.profile.internships.companylocation') }}</th>
                    <th>{{ __('elements.profile.internships.contactperson') }}</th>
                    <th>{{ __('elements.profile.internships.contactemail') }}</th>
                    <th>{{ __('elements.profile.internships.contactphone') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach(Auth::user()->getWorkplaceLearningPeriods() as $wplp)
                    <tr class="{{ (Auth::user()->hasCurrentWorkplaceLearningPeriod() && Auth::user()->getCurrentWorkplaceLearningPeriod()->is($wplp)) ? 'highlight' : '' }}">
                        <td><a href="{{ '/period/edit/'.$wplp->wplp_id }}"><img class="table-icon"
                                                                                src="{{ secure_asset("assets/img/icn-edit.svg") }}"/></a>
                        </td>
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

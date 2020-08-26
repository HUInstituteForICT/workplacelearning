@extends('layout.HUdefault')
@section('title')
Student details
@stop
@section('content')
<?php
use App\Student;use App\Workplace;
/** @var Student $student */?>
<div class="container-fluid">
    <a href="{{ route('teacher-dashboard') }}">
        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> {{ __('errors.returnhome') }}
    </a>
    <br /><br />

    <div class="row">
        <!-- Profile Info -->
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body no-padding">
                    <div class="card-header"> {{ __('general.student-details') }} </div>

                    <div class="card-body">
                        <p class="card-text">{{ $student->studentnr }}</p>
                        <h3 class="card-text">
                            <strong>{{ $student->firstname }} {{ $student->lastname }}</strong><br />
                        </h3>
                        <p class="card-text">{{ $student->email }}</p>
                        <p class="card-text">{{ $student->phonenr }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Stage Info -->
        <div class="col-md-4">
            <div id="alert" class="panel panel-default">
                <div class="panel-body no-padding">
                    <?php
                    /** @var Workplace $workplace */
                    ?>
                    <div class="card-body">
                        <h3 class="card-text">
                            <strong>{{ $workplace->workplaceLearningPeriod->startdate->toFormattedDateString()}} - {{ $workplace->workplaceLearningPeriod->enddate->toFormattedDateString() }}</strong>
                        </h3>
                        <p class="card-text">{{ $workplace->wp_name }}</p>
                        <p class="card-text">{{ $workplace->street }} {{ $workplace->housenr }}, {{ $workplace->postalcode }} {{ $workplace->town }}, {{ $workplace->country }}</p><br />
                        <p class="card-text"><strong>Contactperson </strong><br /></p>
                        <p class="card-text">{{ $workplace->contact_name }}</p>
                        <p class="card-text">{{ $workplace->contact_email }}</p>
                        <p class="card-text">{{ $workplace->contact_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr>

</div>
@stop
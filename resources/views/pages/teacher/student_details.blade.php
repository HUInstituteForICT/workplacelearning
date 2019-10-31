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
                    /** @var Workplace $currentWorkplace */
                    ?>
                    <div class="card-body">
                        <h3 class="card-text">
                            <strong>{{ $currentWorkplace->wp_name }}</strong>
                        </h3>
                        <p class="card-text">{{ $currentWorkplace->street }} {{ $currentWorkplace->housenr }}, {{ $currentWorkplace->postalcode }} {{ $currentWorkplace->town }}, {{ $currentWorkplace->country }}</p><br />
                        <p class="card-text"><strong>Contact: </strong><br /> {{ $currentWorkplace->contact_name }} ({{ $currentWorkplace->contact_email }})</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr>

</div>
@stop
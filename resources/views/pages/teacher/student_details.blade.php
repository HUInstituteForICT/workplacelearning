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
        <div class="col-md-4">
            @card
            <!-- Icon & name -->
                <div class="row">
                    <div class="col-md-4">
                        <span class="glyphicon glyphicon-user user-icon" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-8">
                        <h1>{{ $student->firstname }} {{ $student->lastname }} </p>
                        <h3>{{ $workplace->wp_name }}</h3>
                    </div>
                </div>
                <hr>

            <!-- Period -->
                <div class="row">
                    <div class="col-md-2">
                        <span class="glyphicon glyphicon-calendar calendar-icon" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-8">
                        <p>{{date('d-m-Y', strtotime($workplace->workplaceLearningPeriod->startdate))}}   -   {{date('d-m-Y', strtotime($workplace->workplaceLearningPeriod->enddate))}}</p>
                        <!-- Progress-bar -->
                        <div class="progress">
                                <!-- $numdays is number of valid full working days, aantaluren is the goal number of internship *days* -->
                                <div class="progress-bar progress-bar-success" role="progressbar"
                                    style="width:{{ min(round(($numdays/$period->nrofdays)*100,1),100) }}%">
                                    @if($numdays >= ($period->nrofdays / 2))
                                        {{ $numdays.' / '.($period->nrofdays) }} {{ __('elements.analysis.days') }}
                                        ( {{ round(($numdays/$period->nrofdays)*100,1) }}%)
                                    @endif
                                </div>

                                <div class="progress-bar" role="progressbar"
                                    style="width:{{ min((100-round(($numdays/$period->nrofdays)*100,1)), 100) }}%">
                                    @if($numdays < ($period->nrofdays / 2))
                                        {{ $numdays.' / '.$period->nrofdays }} {{ __('elements.analysis.days') }}
                                        ( {{ round(($numdays/$period->nrofdays)*100,1) }}
                                        %)
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
                <hr>

            <!-- Contact information -->
            <h4>Contact informatie</h4>
            <h4 class="label-information">Email</h4>
            <p>{{ $student->email }}</p>
            <h4 class="label-information">Mobiel</h4>
            <p>{{ $student->phonenr }}</p>
                
            @endcard
        </div>

        <!-- Saved Learning Items -->
        <div class="col-md-8">
            @card
                @if(count($folders) === 0)
                    <div class="alert alert-error">
                        Deze student heeft nog niets met u gedeeld
                    </div>
                @endif

                @foreach($folders as $folder)
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" href="#{{$folder->folder_id}}">{{ $folder->title }}</a>
                            </h4>
                        </div>
                        <div id="{{$folder->folder_id}}" class="panel-collapse collapse">
                        
                            <div class="panel-body">
                                {{$folder->description}}
                                <hr>
                                <strong>Toegevoegde items:</strong>
                                @foreach($sli as $item)
                                    @if($item->category === 'tip' &&  $item->folder === $folder->folder_id)
                                                <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                                                    role="alert">
                                                    <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                                    <p> {{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                                </div>
                                    @endif
                                @endforeach

                                <hr>
                                <h4>Comments</h4>
                                @foreach ($allFolderComments as $comment)
                                    @if ($folder->folder_id === $comment->folder_id)
                                        <div class="panel panel-default">
                                            <div class="panel-body no-padding">
                                                <div class="card-header">
                                                    <strong>{{ $comment->author->firstname }} {{ $comment->author->lastname }}</strong>
                                                    <small class="comment-date">{{date('d-m-Y H:i', strtotime($comment->created_at))}}</small>
                                                </div>

                                                <div class="card-body">
                                                    <p class="card-text">{{ $comment->text }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        <div class="panel-footer">

                            {!! Form::open(array(
                            'url' =>  route('folder.addCommentAsTeacher')))
                            !!}
                            <div class="form-group">
                                <input type='text' value="{{$folder->folder_id}}" name='folder_id' class="form-control folder_id">
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Reageer hier op de student" name='folder_comment' class="form-control folder_comment"></textarea>
                            </div>
                            {{ Form::submit('Verstuur', array('class' => 'btn btn-primary sendComment')) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                @endforeach
            @endcard
        </div>

        

    </div>

    <hr>

</div>
@stop
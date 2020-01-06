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
                                    style="width:{{ min(round(($numdays/$learningperiod->nrofdays)*100,1),100) }}%">
                                    @if($numdays >= ($learningperiod->nrofdays / 2))
                                        {{ $numdays.' / '.($learningperiod->nrofdays) }} {{ __('elements.analysis.days') }}
                                        ( {{ round(($numdays/$learningperiod->nrofdays)*100,1) }}%)
                                    @endif
                                </div>

                                <div class="progress-bar" role="progressbar"
                                    style="width:{{ min((100-round(($numdays/$learningperiod->nrofdays)*100,1)), 100) }}%">
                                    @if($numdays < ($learningperiod->nrofdays / 2))
                                        {{ $numdays.' / '.$learningperiod->nrofdays }} {{ __('elements.analysis.days') }}
                                        ( {{ round(($numdays/$learningperiod->nrofdays)*100,1) }}
                                        %)
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
                <hr>

            <!-- Contact information -->
            <h4>{{ __('dashboard.contact-informatie') }}</h4>
            <h4 class="label-information">{{ __('elements.registration.labels.email') }}</h4>
            <p>{{ $student->email }}</p>
            <h4 class="label-information">{{ __('elements.registration.labels.phone') }}</h4>
            <p>{{ $student->phonenr }}</p>
                
            @endcard
        </div>

        <!-- folders -->
        <div class="col-md-8">
            @if(count($sharedFolders) === 0)
                <div class="custom-alert alert alert-info" role="alert">{{ __('folder.nothing-shared') }}</div>
            @else
            @card
                @foreach($sharedFolders as $folder)
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                <a data-toggle="collapse" href="#{{$folder->folder_id}}">{{ $folder->title }}</a>
                                <div class="clearfix"></div>
                                <p class="sub-title-light">{{ count($folder->savedLearningItems)}} {{ __('folder.items') }}</p>
                                <div class="bullet">&#8226;</div>
                                <p class="sub-title-light">{{ count($folder->folderComments)}} {{ __('folder.comments') }}</p>    
                                </h4>
                            </div>
                            <div id="{{$folder->folder_id}}" class="panel-collapse collapse">

                            {{-- folder basic info --}}
                            <section class="section folder-info">
                                <p class="sub-title-light">{{ __('folder.created-on') }} {{ $folder->created_at->toFormattedDateString() }}</p>
                                <br>
                                {{ $folder->description }}
                            </section>
                            
                            {{-- saved learning items --}}
                            @if (count($folder->savedLearningItems))
                                <hr>
                                <section class="section">
                                    <h5>{{ __('folder.added-items') }} <span class="badge">{{ count($folder->savedLearningItems)}}</span></h5>
                                    @foreach($folder->savedLearningItems as $item)
                                        @if($item->category === 'tip')
                                            <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                                                role="alert">
                                                <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                                <p> {{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </section>
                            @endif
                            
                            {{-- comments --}}
                            @if (count($folder->folderComments))
                                <hr>
                                <section class="section comment-section">
                                    <h5>{{ __('folder.comments') }} <span class="badge">{{ count($folder->folderComments)}}</span></h5>
                                    @foreach ($folder->folderComments as $comment)
                                        @if ($comment->author->isStudent())
                                            <div class="comment student-comment">
                                                <p class="sub-title-light"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{date('H:i', strtotime($comment->created_at))}}</p>
                                                <p class="comment-date sub-title-light">{{ $comment->created_at->toFormattedDateString() }}</p>
                                                <p class="comment-author">{{ $comment->author->firstname }} {{ $comment->author->lastname }}</p>
                                                <p class="card-text">{{ $comment->text }}</p>
                                            </div>
                                        @else
                                            <div class="comment teacher-comment">
                                                <p class="sub-title-light"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{date('H:i', strtotime($comment->created_at))}}</p>
                                                <p class="comment-date sub-title-light">{{ $comment->created_at->toFormattedDateString() }}</p>
                                                <p class="comment-author teacher">{{ $comment->author->firstname }} {{ $comment->author->lastname }}</p>
                                                <p class="card-text">{{ $comment->text }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </section>
                            @endif

                            <div class="panel-footer">

                                {!! Form::open(array(
                                'url' =>  route('folder.addComment')))
                                !!}
                                <div class="form-group">
                                    <input type='text' value="{{$folder->folder_id}}" name='folder_id' class="form-control folder_id">
                                </div>
                                <div class="form-group">
                                    <textarea placeholder="Reageer hier op de student" name='folder_comment' class="form-control folder_comment" maxlength="255"></textarea>
                                </div>
                                {{ Form::submit('Versturen', array('class' => 'right btn btn-primary sendComment')) }}
                                {{ Form::close() }}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endcard
            @endif
        </div>
    </div>

</div>
@stop
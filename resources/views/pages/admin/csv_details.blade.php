@extends('layout.HUdefault')
@section('title')
    Suggestie voor koppelen
@stop
@section('content')
    <?php
    use App\Student;use App\WorkplaceLearningPeriod;
    /** @var Student $student */
    ?>
    <a href="{{ route('admin-linking') }}">
        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> {{ __('errors.returnoverview') }}
    </a>
    <br /><br />
    @if(count($notKnownStudents ) > 0)
    <div class="alert alert-danger" role="alert">
    
    <h4>{{__('linking.alert')}}</h4>
    <p><strong>{{__('linking.alert-text')}}</strong></p>
    <br>
        @foreach($notKnownStudents as $notKnowStudent)
        <ul>
        <li>{{ $notKnowStudent }}</li>
        </ul>
        @endforeach
        <br>
        <button class='btn btn-primary glyphicon glyphicon-download-alt' onclick='downloadNotKnownStudents(<?php echo json_encode($notKnownStudents); ?>)'> Download</button>
    </div>
    @endif
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>{{__('linking.suggestion')}}</h3>
                            <div class="table-responsive">
                                <table class="table table-striped" id="csvSuggestion">

                                    <thead>
                                    <tr>
                                        <th>Naam Docent</th>
                                        <th>Email Docent</th>
                                        <th>Naam Student</th>
                                        <th>Email Student</th>
                                        <th>Stage</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($pairs as $pair)
                                        <tr>
                                            <td>{{ $pair->teacher->firstname}} {{ $pair->teacher->lastname}} </td>
                                            <td>{{ $pair->teacher->email}}</td>
                                            <td>{{ $pair->student->firstname}} {{ $pair->student->lastname}} </td>
                                            <td>{{ $pair->student->email}}</td>
                                            <td>{{ $pair->workplace}}</td>
                            
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <button class='btn btn-info' onclick="getCSTableData()">{{__('linking.agree')}}</button>
                            </div>
                        </div>
                    </div>
                </div>


    </div>
@stop

@include('js.linking')






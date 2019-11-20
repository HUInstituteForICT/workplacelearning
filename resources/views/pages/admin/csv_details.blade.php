@extends('layout.HUdefault')
@section('title')
    Suggestie voor koppelen
@stop
@section('content')
    <?php
    use App\Student;use App\WorkplaceLearningPeriod;
    /** @var Student $student */
    ?>
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>Suggestie voor koppelen</h3>
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
                                            <td>{{ $pair->docent->firstname}} {{ $pair->docent->lastname}} </td>
                                            <td>{{ $pair->docent->email}}</td>
                                            <td>{{ $pair->student->firstname}} {{ $pair->student->lastname}} </td>
                                            <td>{{ $pair->student->email}}</td>
                                            <td>{{ $pair->workplace}}</td>
                            
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <button class='btn btn-info' onclick="getCSTableData()">Akkoord en koppelen</button>
                                <h4>Bij deze studenten is iets misgegaan:</h4>
                                @foreach($notKnownStudents as $notKnowStudent)
                                       <p>{{ $notKnowStudent }}</p>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>


    </div>
@stop

@include('js.linking')






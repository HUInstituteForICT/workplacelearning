@extends('layout.HUdefault')

@section('title')
    Dashboard
@stop

@section('content')
<div class="container-fluid">
    <h1>Dashboard</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close welcome-alert" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p>{{ __('home.welcome-teacher') }}</p>
            </div>
            <h3> {{ __('general.students') }} <span class="badge">{{ count($students) }}</span></h3>
                <?php
                use App\Student;
                use App\Analysis\Producing\ProducingAnalysisCollector;
                /** @var Student $student */ 
                /** @var ProducingAnalysisCollector $producingAnalysisCollector */ 
                ?>

                <div class="table-responsive">
                    <table class="table student-table">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Bedrijf</th>
                                <th>begeleidingsvragen</th>
                                <th>Ingezonden</th>
                                <th>Voortgang</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($students as $student)
                                <tr class="custom-tr">
                                    @if ($student->educationProgram->educationprogramType->isProducing())
                                        @switch($student->priority())
                                            @case($student->priority()['countDaysFromLastActivity'] >= 10)
                                                <td class="red-td danger-icon-red">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break
                                                
                                            @case($student->priority()['countDaysFromLastActivity'] >= 5)
                                                <td class="orange-td danger-icon-orange">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break

                                            @case(count($student->priority()['sharedFoldersWithoutResponse']) >= 1)
                                                <td class="orange-td">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break

                                            @default
                                                <td class="green-td">{{ $student->firstname }} {{ $student->lastname }}</td>
                                        @endswitch
                                        
                                    @elseif ($student->educationProgram->educationprogramType->isActing())
                                        @switch($student->priority())
                                            @case($student->priority()['countDaysFromLastActivity'] >= 30)
                                                <td class="red-td danger-icon-red">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break
                                                
                                            @case($student->priority()['countDaysFromLastActivity'] >= 14)
                                                <td class="orange-td danger-icon-orange">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break

                                            @case(count($student->priority()['sharedFoldersWithoutResponse']) >= 1)
                                                <td class="orange-td">{{ $student->firstname }} {{ $student->lastname }}</td>
                                                @break

                                            @default
                                                <td class="green-td">{{ $student->firstname }} {{ $student->lastname }}</td>
                                        @endswitch
                                    @endif
                                    
                                    <td>{{ $student->getCurrentWorkplace()->wp_name }}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                        @if (count($student->priority()['sharedFoldersWithoutResponse']) === 1)
                                            {{ count($student->priority()['sharedFoldersWithoutResponse']) }} {{ __('folder.unanswered-question') }}
                                        @elseif (count($student->priority()['sharedFoldersWithoutResponse']) >= 2)
                                            {{ count($student->priority()['sharedFoldersWithoutResponse']) }} {{ __('folder.unanswered-questions') }}
                                        @elseif (count($student->priority()['sharedFoldersWithoutResponse']) === 0 && count($student->getSharedFolders()) !== 0)
                                            {{ __('folder.no-new-folders') }}
                                        @elseif (count($student->getSharedFolders()) === 0)
                                            {{ __('folder.nothing-shared') }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="glyphicon glyphicon-glyphicon glyphicon-time" aria-hidden="true"></span>
                                        @if ($student->priority()['daysFromLastActivity'] === '1 seconde geleden')
                                            {{ __('general.today') }}
                                        @else
                                            {{ $student->priority()['daysFromLastActivity'] }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" role="progressbar"
                                                style="width:{{ min(round(($producingAnalysisCollector->getFullWorkingDaysOfStudent($student)/$student->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1),100) }}%">
                                                @if($producingAnalysisCollector->getFullWorkingDaysOfStudent($student) >= ($student->getCurrentWorkplaceLearningPeriod()->nrofdays / 2))
                                                    {{ $producingAnalysisCollector->getFullWorkingDaysOfStudent($student).' / '.($student->getCurrentWorkplaceLearningPeriod()->nrofdays) }} {{ __('elements.analysis.days') }}
                                                    ( {{ round(($producingAnalysisCollector->getFullWorkingDaysOfStudent($student)/$student->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1) }}%)
                                                @endif
                                            </div>
            
                                            <div class="progress-bar" role="progressbar"
                                                style="width:{{ min((100-round(($producingAnalysisCollector->getFullWorkingDaysOfStudent($student)/$student->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1)), 100) }}%">
                                                @if($producingAnalysisCollector->getFullWorkingDaysOfStudent($student) < ($student->getCurrentWorkplaceLearningPeriod()->nrofdays / 2))
                                                    {{ $producingAnalysisCollector->getFullWorkingDaysOfStudent($student).' / '.$student->getCurrentWorkplaceLearningPeriod()->nrofdays }} {{ __('elements.analysis.days') }}
                                                    ( {{ round(($producingAnalysisCollector->getFullWorkingDaysOfStudent($student)/$student->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1) }}
                                                    %)
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="last-td">
                                        <a href="{{ route('teacher-student-details', ['student' => $student]) }}"
                                            class="btn btn-info">{{ __('general.view') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <div class="custom-alert alert alert-info" role="alert">
                                    {{ __('general.no-student') }}
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>

@stop
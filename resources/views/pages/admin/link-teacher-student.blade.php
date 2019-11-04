@extends('layout.HUdefault')

@section('title')
    Admin dashboard
@stop
@section('content')

    <div class="container-fluid">

        <h1>{{ __('linking.overzicht') }}</h1>
        <div class="row">


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>{{ __('linking.docenten') }}</h3>
                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>@sortablelink('firstname', 'First name')</th>
                                    <th>@sortablelink('lastname', 'Last name')</th>
                                    <th>@sortablelink('email', 'E-mail')</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                // use App\Repository\SearchFilter;use App\Student;
                                /** @var Student $student */ ?>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->firstname }}</td>
                                        <td>{{ $teacher->lastname }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>
                                            <button data-target="#myModal" data-toggle="modal" class="btn btn-primary" onclick="chooseDocent({{ $teacher->student_id }})">
                                            {{ __('linking.koppelen') }}
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{ __('linking.student-koppelen') }}</h4>
        </div>
        <div class="modal-body">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('linking.stap-1') }}</h3>
            </div>
            <div class="panel-body">
                <p id="chosenTeacher"></p>
           </div>
        </div>  
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('linking.stap-2') }}</h3>
            </div>
            <div class="panel-body">
            <div class="dropdown">
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="{{ __('linking.placeholder') }}" id="dropdownInput" onkeyup="filterFunction()">
                        <div class="dropdown-links">
                        @foreach($students as $student)
                        <a onclick="chooseStudent({{ $student->student_id }})">{{ $student->studentnr }} - {{ $student->firstname }} {{ $student->lastname }}</a>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="step-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('linking.stap-3') }}</h3>
                </div>
                <div class="panel-body">
                    <select id="selectWPLP" class="form-control" name="wplp">
                        <option>{{ __('linking.kies-stage') }}</option> 
                    </select>
                </div>
            </div>
        </div>

        <div id="error">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('linking.stap-3') }}</h3>
                </div>
                <div class="panel-body">
                    Deze student heeft geen gekoppelde stage.
                </div>
            </div>
        </div>

        
         {!! Form::open(array(
            'url' =>  route('update-workplacelearningperiod')))
            !!}
            <div class="form-group">
                <input id='wplp' type='text', name='wplp_id' class="form-control">
            </div>
                          
            <div class="form-group">
                <input type='text', name='teacher_id' id="teacher_id" class="form-control">
            </div>
            

        <div class="modal-footer">
            {{ Form::submit('Koppelen', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<?php
//This is still necessary because $ students is an object list and it is not iterable.


$allStudents = array();
/** @var Student $student */ 
foreach($students as $student) {
    array_push($allStudents, $student);
};

$allTeachers = array();
foreach($teachers as $teacher) {
    array_push($allTeachers, $teacher);
}

use App\WorkPlaceLearningPeriod;
/** @var WorkPlaceLearningPeriod $wplp */ 

$wplpArray = array();
foreach($wplperiods as $wplp) {
    array_push($wplpArray, $wplp);
}

use App\WorkPlace;
/** @var WorkPlace $workplace */ 

$allWorkplaces = array();
foreach($workplaces as $workplace) {
    array_push($allWorkplaces, $workplace);
}
?>


@include('js.linking')
<script>
    workplacelearningperiods = {!! json_encode($wplpArray) !!};
    students = {!! json_encode($allStudents) !!}
    teachers = {!! json_encode($allTeachers) !!};
    workplaces = {!! json_encode($allWorkplaces) !!};

</script>


@stop
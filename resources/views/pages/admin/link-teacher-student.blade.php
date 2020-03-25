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
                        <h3 id="teacher-overview">{{ __('linking.docenten') }}</h3>
                        <button id="upload-btn" class="btn btn-primary" data-target="#CSV-Modal" data-toggle="modal" >Upload CSV</button>
                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>@sortablelink('firstname', 'First name')</th>
                                    <th>@sortablelink('lastname', 'Last name')</th>
                                    <th>@sortablelink('email', 'E-mail')</th>
                                    <th>@sortablelink('ep_id', 'Education program')</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                use App\Student;
                                /** @var Student $teacher */ ?>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->firstname }}</td>
                                        <td>{{ $teacher->lastname }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>{{ $teacher->educationProgram->ep_name }}</td>
                                        <td>
                                            <button data-target="#Linking-Modal" data-toggle="modal" class="btn btn-info" onclick="chooseDocent({{ $teacher->student_id }})">
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

  <!-- Modal for linking-->
  <div class="modal fade" id="Linking-Modal" role="dialog">
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

            <div class="panel panel-default students-panel">
                <div class="panel-heading" data-toggle="collapse" data-target="#students">
                    <span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
                    <h3 class="panel-title clickable">{{ __('linking.studenten') }}</h3>
                </div>
                <div id="students" class="panel-body collapse">
                    <div id="linked-students" class="list-group nomargin"></div>
                </div>
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
                <div class="panel-body">{{ __('linking.geen-stage') }}</div>
            </div>
        </div>

        
         {!! Form::open(array(
            'url' =>  route('update-teacher-for-workplacelearningperiod')))
            !!}
            <div class="form-group">
                <input id='wplp' type='text' name='wplp_id' class="form-control">
            </div>
                          
            <div class="form-group">
                <input type='text' name='teacher_id' id="teacher_id" class="form-control">
            </div>
            

        <div class="modal-footer">
            {{ Form::submit('Koppelen', array('class' => 'btn btn-info', 'id' => 'coupleButton')) }}
            {{ Form::close() }}
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<!-- Modal for CSV-->
<div class="modal fade" id="CSV-Modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">CSV uploaden</h4>

         
        <div class="modal-body">
            {!! Form::open(array('url' =>  route('update-teacher-for-workplacelearningperiod-csv'),
            'files' => true,'enctype'=>'multipart/form-data')) !!}

            <div class="form-group">
                <input type="file" name="file" id="file" class="form-control">
            </div>

            {{ Form::submit('Upload', array('class' => 'btn btn-info', 'id' => 'coupleButton')) }}
            {{ Form::close() }}

        </div>


      </div>
      
    </div>
  </div>
  
</div>

@include('js.linking')
<script>


    /** @var {array} */
    const workplacelearningperiods = {!! json_encode($workplaceLearningPeriods) !!};
    /** @var {array} */
    const students = {!! json_encode($students) !!}
    /** @var {array} */
    const teachers = {!! json_encode($teachers) !!};

</script>


@stop
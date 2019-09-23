@extends('layout.HUdefault')

@section('title')
    Admin dashboard
@stop
@section('content')

    <div class="container-fluid">

        <h1>Admin dashboard</h1>
        <div class="row">


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>Students</h3>
                        <hr/>
                        <div>
                            <strong>Filters</strong>
                            {{Form::open(['method' => 'GET'])}}
                            <div class="row">
                                @foreach($filters as $filter)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>
                                                {{ __('filters.' . $filter) }}
                                                <input class="form-control" type="text"
                                                       value="{{ request('filter.' . $filter, '') }}"
                                                       name="filter[{{$filter}}]"/>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="btn btn-info">Filter</button>
                            {{Form::close()}}

                        </div>

                        <hr/>


                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>Student number</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>E-mail</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                use App\Student;

/* @var Student $student */ ?>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->studentnr }}</td>
                                        <td>{{ $student->firstname }}</td>
                                        <td>{{ $student->lastname }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            @if($student->userlevel === 1)
                                                <span class="label label-info">Teacher</span>
                                            @elseif($student->userlevel === 2)
                                                <span class="label label-danger">Admin</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin-student-details', ['$student' => $student])}}">
                                                details
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>

@stop
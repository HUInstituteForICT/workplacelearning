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
                            {{ Form::open(['method' => 'GET']) }}

                            <input type="hidden" name="sort" value="{{ request('sort') }}" />
                            <input type="hidden" name="direction" value="{{ request('direction') }}" />
                            <div class="row">
                                <?php
                                /** @var SearchFilter $filter */
                                ?>
                                @foreach($filters as $filter)

                                    @if($filter->isTextType())
                                        @include('pages.admin.filters.filter_text', ['filter' => $filter])
                                    @elseif($filter->isSelectType())
                                        @include('pages.admin.filters.filter_select', ['filter' => $filter])
                                    @endif
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
                                    <th>@sortablelink('studentnr', 'Student number')</th>
                                    <th>@sortablelink('firstname', 'First name')</th>
                                    <th>@sortablelink('lastname', 'Last name')</th>
                                    <th>@sortablelink('email', 'E-mail')</th>
                                    <th>@sortablelink('userlevel', 'Role')</th>
                                    <th>@sortablelink('registrationdate', 'Registration date')</th>
                                    <th>Programme</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                use App\Repository\SearchFilter;use App\Student;
                                /** @var Student $student */ ?>
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
                                            {{ $student->registrationdate->format('m/d/Y') }}
                                        </td>
                                        <td>
                                            {{ $student->educationProgram->ep_name }}
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
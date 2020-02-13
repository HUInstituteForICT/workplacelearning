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
                /** @var Student $student */ ?>
                @forelse($students as $student)
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="card-body flex-cnt-sb">
                                <div>
                                    <h5 class="card-title">{{ $student->firstname }} {{ $student->lastname }}</h5>
                                    <p class="card-text">{{ $student->email }}</p>
                                </div>
                                <a href="{{ route('teacher-student-details', ['student' => $student]) }}"
                                    class="btn btn-info">{{ __('general.view') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="custom-alert alert alert-info" role="alert">
                        {{ __('general.no-student') }}
                    </div>
                @endforelse
        </div>
    </div>
</div>

@stop
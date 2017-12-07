@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>{{ trans('statistics.create-new') }}</h4>
                @foreach($educationProgramTypes as $educationProgramType)
                    <a href="{{ route('statistics.create', ['id' => $educationProgramType->eptype_id]) }}">{{ $educationProgramType->eptype_name }} {{ trans('statistics.statistic') }}</a><br/>
                @endforeach
            </div>
        </div>
        <div class="row">
            <h3>{{ trans('statistics.all-statistics') }}</h3>
            <ul>
                @foreach($statistics as $statistic)
                    <li style="margin-top:15px;">
                        {{ $statistic->name }} ({{ $statistic->educationProgramType->eptype_name }})
                        {{ Form::open(['route' => ['statistics.destroy', $statistic->id], 'method' => 'delete']) }}
                            <button class="btn btn-primary">{{ trans('react.delete') }}</button>
                        {{ Form::close() }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop

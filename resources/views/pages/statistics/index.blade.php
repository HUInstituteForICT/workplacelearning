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
                    <a href="{{ route('statistics.create', ['id' => $educationProgramType->eptype_id]) }}">{{ $educationProgramType->eptype_name }} {{ trans('statistics.statistic') }}</a>
                    <br/>
                @endforeach
            </div>
        </div>
        <h3>{{ trans('statistics.all-statistics') }}</h3>
        <div class="row">
            @foreach($statistics as $statistic)
                <div class="col-md-4 col-md-offset-1 panel panel-default">
                    <div class="panel-body">
                        <strong>Name:</strong> {{ $statistic->name }}<br/>
                        <strong>EP Type:</strong> {{ $statistic->educationProgramType->eptype_name }}<br/>
                        <strong>Calculation:</strong><br/> {{ $statistic->getStatisticCalculationExpression() }}
                        <br/><br/>

                        {{ Form::open(['route' => ['statistics.destroy', $statistic->id], 'method' => 'delete']) }}
                        <button class="btn btn-danger">{{ strtolower(trans('react.delete')) }}</button>
                        {{ Form::close() }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@stop

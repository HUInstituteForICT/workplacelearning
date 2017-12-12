@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <h1>{{ trans('tips.tips') }}</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <h2>{{ $tip->name }}</h2>
                @if(count($tip->statistics) > 0)
                    <a href="{{ route('tips.edit', ['id' => $tip->id]) }}">{{ trans('tips.to-tip') }}</a>
                @endif
                <h4>{{ trans('tips.form.selecting-statistic') }}</h4>

                {{ Form::open(['route' => ['tips.couple-statistic', $tip->id], 'method' => 'put']) }}


                <div class="form-group">
                    <label for="id">{{ trans('tips.form.statistic') }}</label>
                    {{ Form::select('id', $statistics, null, ["class" => "form-control"]) }}
                    <a href="{{ route('statistics.index') }}" target="_blank">{{ trans('statistics.go-to') }}</a>
                </div>

                <div class="form-group">
                    <label for="comparison_operator">{{ trans('tips.form.comparison-operator') }}</label>
                    {{ Form::select('comparison_operator', $comparisonOperators, null, ["class" => "form-control"]) }}
                </div>

                <div class="form-group">
                    <label for="threshold">{{ trans('tips.form.threshold') }}</label>
                    {{ Form::number('threshold', null, ['class' => 'form-control', 'step' => 'any']) }}
                </div>

                <div class="form-group">
                    <label for="multiplyBy100">
                        {{ Form::checkbox('multiplyBy100', 1, true) }}
                        {{ trans('tips.form.multiplyBy100') }}
                    </label>
                </div>

                <button name="save-and" value="again" type="submit"
                        class="btn defaultButton">{{ trans('tips.form.save-statistic-and-again') }}</button>
                <br/><br/>
                <button name="save-and" value="continue" type="submit"
                        class="btn defaultButton">{{ trans('tips.form.save-statistic-and-continue') }}</button>

                {{ Form::close() }}
            </div>

            @if(count($alreadyCoupledStatistics) > 0)
                <div class="col-md-8 col-md-offset-1">
                    <h3>{{ trans('tips.this-tip-statistics') }}</h3>
                    <div class="row">


                        @foreach($alreadyCoupledStatistics as $statistic)

                            <div class="col-md-3 col-md-offset-1 panel panel-default">
                                <div class="panel-body">
                                    <strong>Name:</strong> {{ $statistic->name }}<br/>
                                    <strong>EP Type:</strong> ({{ $statistic->educationProgramType->eptype_name }})<br/>
                                    <strong>If:</strong> {{ $statistic->pivot->ifExpression() }}
                                </div>
                            </div>


                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

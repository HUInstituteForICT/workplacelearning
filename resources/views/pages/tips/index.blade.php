@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <h1>{{ trans('tips.tips') }}</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <h4>{{ trans('tips.create-new') }}</h4>
                {{ Form::model($newTip, ['route' => 'tips.store']) }}

                <div class="form-group">
                    <label for="name">{{ trans('tips.form.name') }}</label>
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="statistic[id]">{{ trans('tips.form.statistic') }}</label>
                    {{ Form::select('statistic[id]', $statistics, null, ["class" => "form-control"]) }}
                </div>
                <a href="{{ route('statistics.index') }}" class="btn defaultButton">{{ trans('statistics.go-to') }}</a>
                <br/>

                <div class="form-group">
                    <label for="threshold">{{ trans('tips.form.threshold') }}</label>
                    {{ Form::number('threshold', null, ['class' => 'form-control', 'min' => '0.01', 'max' => '1', 'step' => '0.01']) }}
                </div>

                <div class="form-group">
                    <label for="tipText">{{ trans('tips.form.tipText') }}</label>
                    {{ Form::textarea('tipText', null, ['class' => 'form-control', 'max' => '1000', 'rows' => '3', 'placeholder' => trans('tips.form.tipTextPlaceholder')]) }}
                </div>

                <div class="form-group">
                    <label for="multiplyBy100">
                        {{ Form::checkbox('multiplyBy100', 1, true) }}
                        {{ trans('tips.form.multiplyBy100') }}
                    </label>
                </div>

                <div class="form-group">
                    <label for="showInAnalysis">
                        {{ Form::checkbox('showInAnalysis', 1, true) }}
                        {{ trans('tips.form.showInAnalysis') }}
                    </label>
                </div>

                <button type="submit" class="btn defaultButton">{{ trans('tips.form.submit') }}</button>

                {{ Form::close() }}
            </div>

            <div class="col-md-8 col-md-offset-1">
                <h3>{{ trans('tips.all-tips') }}</h3>
                <div class="row">
                    @foreach($tips as $tip)

                        <div class="col-md-3 col-md-offset-1 panel panel-default">
                            <div class="panel-body">
                                <strong>Name:</strong> {{ $tip->name }}<br/>
                                <strong>EP Type:</strong> ({{ $tip->statistic->educationProgramType->eptype_name }}
                                )<br/>
                                <strong>Statistic:</strong> {{ $tip->statistic->name }}
                                <br/><br/>

                            </div>
                            <div class="panel-footer row">
                                <div class="col-md-5">
                                    <a class="btn btn-primary" href="{{ route('tips.edit', ['id' => $tip->id]) }}">
                                        {{ strtolower(trans('general.edit')) }}
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    {{ Form::open(['route' => ['tips.destroy', $tip->id], 'method' => 'delete']) }}
                                    <button class="btn btn-danger">{{ strtolower(trans('react.delete')) }}</button>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>


                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

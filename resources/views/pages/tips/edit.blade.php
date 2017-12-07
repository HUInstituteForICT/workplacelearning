@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <h1>{{ trans('tips.tips') }}</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <h4>{{ trans('tips.edit') }}</h4>
                {{ Form::model($tip, ['route' => ['tips.update', $tip->id], 'method' => 'put']) }}

                <div class="form-group">
                    <label for="name">{{ trans('tips.form.name') }}</label>
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="statistic[id]">{{ trans('tips.form.statistic') }}</label>
                    {{ Form::select('statistic[id]', $statistics, null, ["class" => "form-control"]) }}
                </div>

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
                        {{ Form::checkbox('multiplyBy100', 1) }}
                        {{ trans('tips.form.multiplyBy100') }}
                    </label>
                </div>

                <div class="form-group">
                    <label for="showInAnalysis">
                        {{ Form::checkbox('showInAnalysis', 1) }}
                        {{ trans('tips.form.showInAnalysis') }}
                    </label>
                </div>

                <button type="submit" class="btn defaultButton">{{ trans('tips.form.save') }}</button>

                {{ Form::close() }}
            </div>

            <div class="col-md-5">
                <h4>{{ trans('tips.form.cohorts-enable') }}</h4>

                {{ Form::model($tip, ['route' => ['tips.updateCohorts', $tip->id], 'method' => "put"]) }}

                <div class="form-group">
                    <label for="enabledCohorts[]">{{ trans('tips.form.enabledCohorts') }}</label>
                    {{ Form::select('enabledCohorts[]', $cohorts, null, ["class" => "form-control", "multiple" => true]) }}
                </div>

                <button type="submit" class="btn defaultButton">{{ trans('tips.form.save') }}</button>


                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

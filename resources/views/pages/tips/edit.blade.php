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
                    <label for="tipText">{{ trans('tips.form.tipText') }}</label>
                    {{ Form::textarea('tipText', null, ['class' => 'form-control', 'max' => '1000', 'rows' => '3', 'placeholder' => trans('tips.form.tipTextPlaceholder')]) }}
                    <br/>
                    <strong>{{ trans('tips.form.statistic-value-parameters') }}</strong>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ trans('tips.form.table-statistic') }}</th>
                            <th>{{ trans('tips.form.table-value-parameter') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tip->buildTextParameters() as $coupledStatistic => $valueId)
                            <tr>
                                <td>{{ $coupledStatistic }}</td>
                                <td><strong>{{ $valueId }}</strong></td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
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

            <div class="col-md-3 col-md-offset-1">

                <h4>{{ trans('tips.this-tip-statistics') }}</h4>
                <a href="{{ route('tips.select-statistic', ['id' => $tip->id]) }}">{{ trans('tips.add-statistic') }}</a>
                <div class="row">


                    @foreach($tip->statistics as $statistic)

                        <div class="col-md-12 panel panel-default">
                            <div class="panel-body">
                                <strong>Name:</strong> {{ $statistic->name }}<br/>
                                <strong>EP Type:</strong> ({{ $statistic->educationProgramType->eptype_name }})<br/>
                                <strong>If:</strong> {{ $statistic->pivot->ifExpression() }}
                            </div>
                            <div class="panel-footer row">
                                <div class="col-md-12 text-right">
                                    {{ Form::open(['route' => ['tips.decouple-statistic', $tip->id, $statistic->pivot->id], 'method' => 'delete']) }}
                                    <button class="btn btn-danger">{{ strtolower(trans('react.delete')) }}</button>
                                    {{ Form::close() }}
                                </div>

                            </div>
                        </div>


                    @endforeach
                </div>
            </div>

            <div class="col-md-3 col-md-offset-1">
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

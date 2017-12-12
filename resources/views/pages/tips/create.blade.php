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
                {{ Form::model($tip, ['route' => 'tips.store']) }}

                <div class="form-group">
                    <label for="name">{{ trans('tips.form.name') }}</label>
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="showInAnalysis">
                        {{ Form::checkbox('showInAnalysis', 1, true) }}
                        {{ trans('tips.form.showInAnalysis') }}
                    </label>
                </div>

                <button type="submit" class="btn defaultButton">{{ trans('tips.form.next-step') }}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

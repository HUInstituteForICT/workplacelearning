@extends('layout.HUdefault')
@section('title')
    {{ __('general.bugreport') }}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h2>{{ __('general.tipsbugs') }}</h2>
                <p>{{ __('general.tips-text') }}</p>
                {!! Form::open(array('id' => 'taskForm', 'class' => 'form-horizontal well', 'url' => route('bugreport-create'))) !!}
                <input type="text" class="form-control" name="feedback_subject"
                       value="{{ old('feedback_subject') }}"
                       placeholder="{{ __('general.tips-what') }}"/><br><br/>
                <textarea class="form-control" style="max-width: 100%;" name="feedback_description" cols="80" rows="10"
                          placeholder="{{ __('general.tips-example') }}">{{ old('feedback_description') }}</textarea>
                <br/>
                <input type="submit" value="{{ __('general.send') }}"/>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
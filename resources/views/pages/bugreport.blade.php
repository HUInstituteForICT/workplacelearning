@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('general.bugreport') }}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h2>{{ Lang::get('general.tipsbugs') }}</h2>
                <p>{{ Lang::get('general.tips-text') }}</p>
                {!! Form::open(array('id' => 'taskForm', 'class' => 'form-horizontal well', 'url' => route('bugreport-create'))) !!}
                <input type="text" class="form-control" name="feedback_subject"
                       value="{{ old('feedback_subject') }}"
                       placeholder="{{ Lang::get('general.tips-what') }}"/><br><br/>
                <textarea class="form-control" style="max-width: 100%;" name="feedback_description" cols="80" rows="10"
                          placeholder="{{ Lang::get('general.tips-example') }}">{{ old('feedback_description') }}</textarea>
                <br/>
                <input type="submit" value="{{ Lang::get('general.send') }}"/>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
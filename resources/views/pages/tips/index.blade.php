@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <h1>{{ trans('tips.tips') }}</h1>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('tips.create') }}" class="">{{ trans('tips.create-new') }}</a>
            <h3>{{ trans('tips.all-tips') }}</h3>
            <div class="row">
                @foreach($tips as $tip)

                    <div class="col-md-2 col-md-offset-1 panel panel-default">
                        <div class="panel-body">
                            <strong>{{ trans('tips.name') }}:</strong> {{ $tip->name }}<br/>
                            <strong>{{ trans('tips.ep-type') }}:</strong>
                            ({{ $tip->statistics->first()->educationProgramType->eptype_name }})<br/>
                            <strong>{{ trans('tips.statistics-count') }}:</strong> {{ $tip->statistics->count() }}<br/>
                            <strong>{{ trans('tips.likes') }}:</strong> {{ $tip->likes->count() }}

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
@stop

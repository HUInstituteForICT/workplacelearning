@extends('layout.HUdefault')
@section('title', 'Analyses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ __('analyses.title') }}</h1>
                <p>{{ __('dashboard.analyses') }}:</p>
                @forelse($analyses as $analysis)
                    <a class="list-group-item"
                       href="{{ route('analytics-show', $analysis->id) }}">{{ $analysis->name }}</a>
                @empty
                    <h3>{{ __('dashboard.analysis-none') }}</h3>
                    <p>{{ __('dashboard.analysis-create') }}</p>
                @endforelse
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('analytics-create') }}" class="btn btn-primary">{{ __('general.new') }}</a>
                <a href="{{ route('analytics-expire-all') }}">{{ trans('analysis.expire-all') }}</a>
            </div>
        </div>
    </div>
@stop
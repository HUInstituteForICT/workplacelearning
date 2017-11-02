@extends('layout.HUdefault')
@section('title', 'Analyses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <p>{{ Lang::get('dashboard.analyses') }}:</p>
                @forelse($analyses as $analysis)
                    <a class="list-group-item"
                       href="{{ route('analytics-show', $analysis->id) }}">{{ $analysis->name }}</a>
                @empty
                    <h3>{{ Lang::get('dashboard.analysis-none') }}</h3>
                    <p>{{ Lang::get('dashboard.analysis-create') }}</p>
                @endforelse
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('analytics-create') }}" class="btn btn-primary">{{ Lang::get('general.new') }}</a>
            </div>
        </div>
    </div>
@stop
@extends('layout.HUdefault')
@section('title', 'Analyses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <p>Analyses:</p>
                @forelse($analyses as $analysis)
                    <a class="list-group-item"
                       href="{{ route('analytics-show', $analysis->id) }}">{{ $analysis->name }}</a>
                @empty
                    <h3>No analyses are found</h3>
                    <p>Do you want to create one?</p>
                @endforelse
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('analytics-create') }}" class="btn btn-primary">New</a>
            </div>
        </div>
    </div>
@stop
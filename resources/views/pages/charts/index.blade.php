@extends('layout.HUdefault')
@section('title', Lang::get('charts.title'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('charts.title') }}</h1>
                <ul>
                    @forelse($analyses as $analysis)
                        <li>
                            <a href="{{ route('analytics-show', $analysis->id) }}">{{ $analysis->name }}</a>
                            @forelse($analysis->charts as $chart)
                                <ul>
                                    <li>
                                        <a href="{{ route('dashboard.charts.show', $chart->id) }}">{{ $chart->label }}</a>
                                    </li>
                                </ul>
                        </li>
                    @empty
                    @endforelse
                    @empty
                        <li>
                            <p>There are no charts.</p>
                            <a href="{{ route('dashboard.charts.create') }}" class="btn btn-primary">Create one</a>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@stop
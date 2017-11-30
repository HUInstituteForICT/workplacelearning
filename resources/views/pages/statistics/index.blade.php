@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <ul>
                @foreach($statistics as $statistic)
                    <li>
                        <a href="{{ route('statistics.edit', ['id' => $statistic->id]) }}">{{ $statistic->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop

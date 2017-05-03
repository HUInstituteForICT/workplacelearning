@extends('layout.HUdefault')
@section('title', 'Analyses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                @if(count($analyses) == 0)
                    <h3>No analyses are found</h3>
                    <p>Do you want to create one?</p>
                    <form action="{{route('analyses-create')}}">
                        <button class="btn btn-primary">Press here to create a new analysis</button>
                    </form>
                @else
                <p>Analyses:</p>
                @foreach($analyses as $analysis)
                    <a class="list-group-item"
                       href="{{ route('analyses-show', $analysis->id) }}">{{ $analysis->name }}</a>
                @endforeach
                @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px"
                                                        height="16px"/> Heb je tips of vragen? Geef ze aan ons door!</a>
            </div>
        </div>
    </div>
@stop
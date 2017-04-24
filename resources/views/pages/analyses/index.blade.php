@extends('layout.HUdefault')
@section('title', 'Analyses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <p>Analyses:</p>
                <ul>
                    @foreach($analyses as $analysis):
                    <li>{{ $analysis->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> Heb je tips of vragen? Geef ze aan ons door!</a>
            </div>
        </div>
    </div>
@stop
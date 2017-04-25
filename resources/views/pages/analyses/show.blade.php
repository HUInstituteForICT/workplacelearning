@extends('layout.HUdefault')
@section('title', 'Analyses - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <dl>
                
                <dt>Name</dt>
                <dd class="well">{{ $anal->name }}</dd>

                <dt>Cached for</dt>
                <dd class="well">{{ $anal->cache_duration }} seconds</dd>
                
                <dt>Query</dt>
                <dd class="well">{{ $anal->query }}</dd>

                <dt>Data</dt>
                <dd class="well">Lorem ipsum dolor sit amet</dd>
                </dl>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px"
                                                        height="16px"/> Heb je tips of vragen? Geef ze aan ons door!</a>
            </div>
        </div>
    </div>
@stop
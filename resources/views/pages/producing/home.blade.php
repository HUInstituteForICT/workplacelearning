@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <h1>{{ Lang::get('dashboard.title') }}</h1>
                    <p>{{ Lang::get('home.welcome') }}
                        <br /><br />{{ Lang::get('home.see-menu') }}</p>
                    <ul>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.learningproces') }}</b> {{ Lang::get('home.steps.1') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.progress') }}</b> {{ Lang::get('home.steps.2') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.analysis') }}</b> {{ Lang::get('home.steps.3') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.deadlines') }}</b> {{ Lang::get('steps.4') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.profile') }}</b> {{ Lang::get('home.steps.5') }}</li>
                    </ul>
                    <p>{{ Lang::get('home.goodluck') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                        <h1>{{ Lang::get('dashboard.lastWZHtitle') }}</h1>
                        @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(5) as $a)
                            <div class="dash-bar">
                                <?php
                                $fmt = new IntlDateFormatter(
                                        (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                                        IntlDateFormatter::GREGORIAN,
                                        IntlDateFormatter::NONE,
                                        NULL,
                                        NULL,
                                        "EEEE dd-MM"
                                );
                                ?>
                                <div class="dash-date">
                                    {{ucwords($fmt->format(strtotime($a->date))) }}
                                </div>
                                <div class="dash-description">
                                    <b>{{ $a->description }}</b>
                                </div>
                                <div class="dash-hours"><b>({{ $a->getDurationString() }})</b></div>
                            </div>
                        @endforeach
                    @endif
                    <br /><a href="{{ LaravelLocalization::GetLocalizedURL(null, '/bugreport', array()) }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> {{ Lang::get('home.tips') }}</a>
                </div>
            </div>
        </div>
@stop

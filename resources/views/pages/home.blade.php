@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
        <div class="container-fluid">
            @if(Auth::user()->getCurrentWorkplaceLearningPeriod() == NULL)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-notice">
                            <span>{{ Lang::get('elements.alerts.notice') }}: </span>{!! str_replace('%s', LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "https://werkplekleren.hu.nl/stageperiode/edit/0", array()), Lang::get('dashboard.nointernshipactive')) !!}
                        </div>
                    </div>
                </div>
            @endif
            @if(count($errors) > 0 || session()->has('success'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                            <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-7">
                    <h1>{{ Lang::get('dashboard.title') }}</h1>
                    <p>Welkom bij Werkplekleren Hogeschool Utrecht. Met dit system krijg je inzicht in je leerproces tijdens het werken in de beroepspraktijk, bijvoorbeeld tijdens je stage. Met dit inzicht kun je nog meer uit je stage halen en jezelf optimaal ontwikkelen!</p>
                    <p>In het menu zie je een aantal tegels. Via de tegel Leerproces kun je je werkzaamheden invoeren. Via de tegel Analyse kun je verschillende inzichten krijgen over wat je geleerd hebt, over hoe je geleerd hebt en wat je nog moeilijk vindt. Via de tegel Voortgang kun je alle ingevoerde data terugvinden en een PDF downloaden met de weekstaten die je moet laten ondertekenen door je bedrijfsbegeleider.</p>
                    <p>Veel succes en plezier tijdens jouw leren op de werkplek!</p>
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
                    <br /><a href="{{ LaravelLocalization::GetLocalizedURL(null, '/bugreport', array()) }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> Heb je tips of vragen? Geef ze aan ons door!</a>
                </div>
            </div>
        </div>
@stop

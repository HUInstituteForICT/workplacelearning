@extends('layout.HUdefault')
@section('title')
    Analyse
@stop
@section('content')

    <div class="container-fluid">

        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()->getCurrentInternshipPeriod() != null && Auth::user()->getCurrentInternshipPeriod()->hasLoggedHours())
            <div class="row">
                <div class="col-lg-6">
                    <h1>{{ Lang::get('rapportages.pageheader') }}</h1>
                    <p>{{ Lang::get('elements.analysis.analysisexplanation') }}</p>
                    <p>{{ Lang::get('elements.analysis.analysischoice') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h3>{{ Lang::get('elements.analysis.workingdaysheader') }}</h3>
                    <p>{{ Lang::get('elements.analysis.workingdaysexplained') }}</p>
                </div>
                <div class="col-lg-1">
                    <p>{{ Lang::get('elements.analysis.numberofdays') }} </p>
                </div>
                <div class="col-lg-4">
                    <div class="progress">
                        <!-- $numdays is number of valid full working days, aantaluren is the goal number of internship *days* -->
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{ round(($numdays/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%">
                            @if($numdays >= (Auth::user()->getCurrentInternshipPeriod()->aantaluren / 2))
                                {{ $numdays." / ".(Auth::user()->getCurrentInternshipPeriod()->aantaluren) }} {{ Lang::get('elements.analysis.days') }} ( {{ round(($numdays/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%)
                            @endif
                        </div>
                        <div class="progress-bar" role="progressbar" style="width:{{ (100-round(($numdays/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1)) }}%">
                            @if($numdays < (Auth::user()->getCurrentInternshipPeriod()->aantaluren / 2))
                                {{ $numdays." / ".(Auth::user()->getCurrentInternshipPeriod()->aantaluren) }} {{ Lang::get('elements.analysis.days') }} ( {{ round(($numdays/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%)
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>{{ Lang::get('elements.analysis.choice') }}</h3>
                    <?php
                        $intlfmt = new IntlDateFormatter(
                                (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                                IntlDateFormatter::GREGORIAN,
                                IntlDateFormatter::NONE,
                                NULL,
                                NULL,
                                "MMMM YYYY"
                        );
                        $begin  = strtotime((new DateTime(Auth::user()->getCurrentInternshipPeriod()->startdatum))->modify("first day of this month")->format('Y-m-d'));
                        $end    = strtotime((new DateTime(Auth::user()->getCurrentInternshipPeriod()->einddatum))->modify("last day of this month")->format('Y-m-d'));
                    ?>
                    <a href="{{ LaravelLocalization::GetLocalizedURL(null, '/analyse/all/all', array()) }}">{{ Lang::get('elements.analysis.showall') }}</a><br />
                    @while($end > $begin)
                        @if($end <= strtotime((new DateTime("now"))->modify("last day of this month")->format('Y-m-d')))
                            <a href="{{ LaravelLocalization::GetLocalizedURL(null, '/analyse/'.date('Y', $end).'/'.date('m', $end), array()) }}">{{ ucwords($intlfmt->format($end)) }}</a><br />
                        @endif
                        <?php $end = strtotime("-1 month", $end); ?>
                    @endwhile
                </div>
            </div>

        @endif

    </div>

@stop

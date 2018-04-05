@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('analysis.analysis') }}
@stop
@section('content')

    <div class="container-fluid">

        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
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
                    <p>{{ Lang::get('elements.analysis.workingdaysexplained', ['hours' => Auth::user()->getCurrentWorkplaceLearningPeriod()->hours_per_day]) }}</p>
                </div>
                <div class="col-lg-1">
                    <p>{{ Lang::get('elements.analysis.numberofdays') }} </p>
                </div>
                <div class="col-lg-4">
                    <div class="progress">
                        <!-- $numdays is number of valid full working days, aantaluren is the goal number of internship *days* -->
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{ min(round(($numdays/Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1),100) }}%">
                            @if($numdays >= (Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays / 2))
                                {{ $numdays." / ".(Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays) }} {{ Lang::get('elements.analysis.days') }} ( {{ round(($numdays/Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1) }}%)
                            @endif
                        </div>
                        <div class="progress-bar" role="progressbar" style="width:{{ min((100-round(($numdays/Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1)), 100) }}%">
                            @if($numdays < (Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays / 2))
                                {{ $numdays." / ".(Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays) }} {{ Lang::get('elements.analysis.days') }} ( {{ round(($numdays/Auth::user()->getCurrentWorkplaceLearningPeriod()->nrofdays)*100,1) }}%)
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
                                App::getLocale(),
                                IntlDateFormatter::GREGORIAN,
                                IntlDateFormatter::NONE,
                                NULL,
                                NULL,
                                "MMMM YYYY"
                        );
                        $begin  = strtotime((new DateTime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate))->modify("first day of this month")->format('Y-m-d'));
                        $end    = strtotime((new DateTime(Auth::user()->getCurrentWorkplaceLearningPeriod()->enddate))->format('Y-m-d'));
                    ?>
                    <a href="{{ route('analysis-producing-detail', ["month" => "all", "year" => "all"]) }}">{{ Lang::get('elements.analysis.showall') }}</a><br />
                    @while($end > $begin)
                        @if($end <= strtotime((new DateTime("now"))->modify("last day of this month")->format('Y-m-d')))
                            <a href="{{ route('analysis-producing-detail', ["month" => date('m', $end), "year" => date('Y', $end)]) }}">{{ ucwords($intlfmt->format($end)) }}</a><br />
                        @endif
                        <?php $end = strtotime("last day of previous month", $end); ?>
                    @endwhile
                </div>
            </div>

        @endif

    </div>

@stop

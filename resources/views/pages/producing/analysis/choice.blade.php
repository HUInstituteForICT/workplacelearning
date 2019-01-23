<?php
/** @var \App\WorkplaceLearningPeriod $period */
/** @var int $start */
/** @var int $end */
?>

@extends('layout.HUdefault')
@section('title')
    {{ __('analysis.analysis') }}
@stop
@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6">
                <h1>{{ __('rapportages.pageheader') }}</h1>
                <p>{{ __('elements.analysis.analysisexplanation') }}</p>
                <p>{{ __('elements.analysis.analysischoice') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h3>{{ __('elements.analysis.workingdaysheader') }}</h3>
                <p>{{ __('elements.analysis.workingdaysexplained', ['hours' => $period->hours_per_day]) }}</p>
            </div>
            <div class="col-lg-1">
                <p>{{ __('elements.analysis.numberofdays') }} </p>
            </div>
            <div class="col-lg-4">
                <div class="progress">
                    <!-- $numdays is number of valid full working days, aantaluren is the goal number of internship *days* -->
                    <div class="progress-bar progress-bar-success" role="progressbar"
                         style="width:{{ min(round(($numdays/$period->nrofdays)*100,1),100) }}%">
                        @if($numdays >= ($period->nrofdays / 2))
                            {{ $numdays.' / '.($period->nrofdays) }} {{ __('elements.analysis.days') }}
                            ( {{ round(($numdays/$period->nrofdays)*100,1) }}%)
                        @endif
                    </div>
                    <div class="progress-bar" role="progressbar"
                         style="width:{{ min((100-round(($numdays/$period->nrofdays)*100,1)), 100) }}%">
                        @if($numdays < ($period->nrofdays / 2))
                            {{ $numdays.' / '.$period->nrofdays }} {{ __('elements.analysis.days') }}
                            ( {{ round(($numdays/$period->nrofdays)*100,1) }}
                            %)
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>{{ __('elements.analysis.choice') }}</h3>
                <a href="{{ route('analysis-producing-detail', ['month' => 'all', 'year' => 'all']) }}">
                    {{ __('elements.analysis.showall') }}
                </a>
                <br/>
                @while($end >= $start)
                    @if($end <= strtotime((new DateTime)->modify('last day of this month')->format('Y-m-d')))
                        <a href="{{ route('analysis-producing-detail', ['month' => date('m', $end), 'year' => date('Y', $end)]) }}">
                            {{ ucwords($formatter->format($end)) }}
                        </a>
                        <br/>
                    @endif
                    <?php $end = strtotime('first day of previous month', $end); ?>
                @endwhile
            </div>
        </div>


    </div>

@stop

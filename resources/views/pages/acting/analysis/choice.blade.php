<?php
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
            <div class="col-md-12">
                <h3>{{ __('elements.analysis.choice') }}</h3>
                <a href="{{ route('analysis-acting-detail', ['month' => 'all', 'year' => 'all']) }}">
                    {{ __('elements.analysis.showall') }}
                </a>
                <br/>
                @while($end > $start)
                    @if($end <= strtotime((new DateTime)->modify('last day of this month')->format('Y-m-d')))
                        <a href="{{ route('analysis-acting-detail', ['month' => date('m', $end), 'year' => date('Y', $end)]) }}">
                            {{ ucwords($formatter->format($end)) }}
                        </a>
                        <br/>
                    @endif
                    <?php $end = strtotime('last day of previous month', $end); ?>
                @endwhile
            </div>
        </div>


    </div>

@stop

<?php
/**
 * This file (progress.blade.php) was created on 06/29/2016 at 12:19.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('home.progress') }}
@stop
@section('content')
    <div class="container-fluid">
        <!-- Internship Info -->
        <div class="row">

            <div class="col-md-12">
                <h3>{{ Lang::get('general.weekstates') }}</h3>
                <div id="ProducingWeekStatesExport" class="__reactRoot" data-latest="{{ $weekStatesDates['latest'] }}"
                     data-earliest="{{ $weekStatesDates['earliest'] }}"
                     data-url="{{ route('report-producing-export') }}"></div>
            </div>
        </div>
        <div class="row" style="margin-top:50px;">
            <div class="col-md-12">
                <h3>{{ Lang::get('home.progress') }}</h3>
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
            </script>

                <div class="row">
                    <div id="ActivityProducingProcessTable" class="__reactRoot col-md-12"></div>
                </div>
            </div>
        </div>

    </div>
@stop

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
            <div class="col-md-11">
                <h3>{{ Lang::get('general.learningmoments') }}</h3>
            </div>
        </div>

        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
                window.reflectionDownloadMultipleUrl = '{{ route('reflection-download-multiple') }}';
            </script>

            <div id="ActivityActingProcessTable" class="__reactRoot col-md-12"></div>
        </div>
    </div>
@stop

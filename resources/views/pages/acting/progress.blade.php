<?php
/**
 * This file (progress.blade.php) was created on 06/29/2016 at 12:19.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('home.progress') }}
@stop
@section('content')
    <div class="container-fluid">
        <h3>{{ __('general.learningmoments') }}</h3>

        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
                window.reflectionDownloadMultipleUrl = '{{ route('reflection-download-multiple') }}';
                window.exportActivitiesUrl = '{{ route('acting-activities-word-export') }}';
                window.mailExportActivitiesUrl = '{{ route('mail-acting-activities-word-export') }}';
                window.activityActingTableMode = 'detail';
                window.progressLink = '{{ route('progress-acting') }}';
            </script>

            <div id="ActivityActingProcessTable" class="__reactRoot col-md-12"></div>
        </div>
    </div>
@stop

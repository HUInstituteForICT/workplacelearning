@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ __('dashboard.title') }}</h1>

                @if($evaluatedTip !== null)
                    <div class="alert" style="background-color: #00A1E2; color: white;" role="alert">
                        <h4>{{ __('tips.personal-tip') }}</h4>
                        <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
                        <br/>

                        <p class="text-right">
                            <a class="alert-link" style="color: white;"
                               href="{{ route('analysis-acting-detail', ['year' => 'all', 'month' => 'all']) }}">{{ __('tips.see-more') }}</a>
                        </p>
                    </div>
                @endif
                @if(!$hasStudentDecided)
                        <div class="alert alert-info">
                            {{ __('misc.reflection-beta-text') }}
                            <br/><br/>
                            <a class='btn btn-primary'
                            href='{{ route('reflection-interview-participation', ['participate' => 1]) }}'>{{ __('misc.participate-accept') }}</a>
                            &nbsp;
                            <a href='{{ route('reflection-interview-participation', ['participate' => 0]) }}'>{{ __('misc.participate-decline') }}</a>
                        </div>
                    @endif

                <p>{{ __('home.welcome') }}
                    <br/><br/>{{ __('home.see-menu') }}</p>
                <ul>
                    <li>{{ __('home.with-tile') }}
                        <b>{{ __('home.learningprocess') }}</b> {{ __('home.steps.1') }}</li>
                    <li>{{ __('home.with-tile') }}
                        <b>{{ __('home.progress') }}</b> {{ __('home.steps.2') }}</li>
                    <li>{{ __('home.with-tile') }}
                        <b>{{ __('home.analysis') }}</b> {{ __('home.steps.3') }}</li>
                    <li>{{ __('home.with-tile') }}
                        <b>{{ __('home.deadlines') }}</b> {{ __('home.steps.4') }}</li>
                    <li>{{ __('home.with-tile') }}
                        <b>{{ __('home.profile') }}</b> {{ __('home.steps.5') }}</li>
                </ul>
                <p>{{ __('home.goodluck') }}</p>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <br/><a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}"
                                                             width="16px" height="16px"/> {{ __('home.tips') }}
                </a>
            </div>
        </div>
    </div>
@stop

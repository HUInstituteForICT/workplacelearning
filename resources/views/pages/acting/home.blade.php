@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ Lang::get('dashboard.title') }}</h1>

                @if(!$hasStudentDecidedBeta)
                    <div class="alert alert-info">
                        {{ __('misc.reflection-beta-text') }}
                        <br/><br/>
                        <a class='btn btn-primary'
                           href='{{ route('reflection-beta-participation', ['participate' => 1]) }}'>{{ __('misc.participate-accept') }}</a>
                        &nbsp;
                        <a href='{{ route('reflection-beta-participation', ['participate' => 0]) }}'>{{ __('misc.participate-decline') }}</a>
                    </div>
                @endif

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

                <p>{{ Lang::get('home.welcome') }}
                    <br/><br/>{{ Lang::get('home.see-menu') }}</p>
                <ul>
                    <li>{{ Lang::get('home.with-tile') }}
                        <b>{{ Lang::get('home.learningprocess') }}</b> {{ Lang::get('home.steps.1') }}</li>
                    <li>{{ Lang::get('home.with-tile') }}
                        <b>{{ Lang::get('home.progress') }}</b> {{ Lang::get('home.steps.2') }}</li>
                    <li>{{ Lang::get('home.with-tile') }}
                        <b>{{ Lang::get('home.analysis') }}</b> {{ Lang::get('home.steps.3') }}</li>
                    <li>{{ Lang::get('home.with-tile') }}
                        <b>{{ Lang::get('home.deadlines') }}</b> {{ Lang::get('home.steps.4') }}</li>
                    <li>{{ Lang::get('home.with-tile') }}
                        <b>{{ Lang::get('home.profile') }}</b> {{ Lang::get('home.steps.5') }}</li>
                </ul>
                <p>{{ Lang::get('home.goodluck') }}</p>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <br/><a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}"
                                                             width="16px" height="16px"/> {{ Lang::get('home.tips') }}
                </a>
            </div>
        </div>
    </div>
@stop

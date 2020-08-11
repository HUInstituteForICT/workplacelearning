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

                    @include('components.tip_card', ['title' => __('tips.personal-tip'), 'evaluatedTip' => $evaluatedTip, 'saved' => $itemExists])

                    <p class="text-right">
                        <a class="alert-link" style="color: white;"
                           href="{{ route('analysis-acting-detail', ['year' => 'all', 'month' => 'all']) }}">{{ __('tips.see-more') }}</a>
                    </p>
                @endif


                <p>{{ __('home.welcome-student') }}
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

    <script>
        function likeTip(tipId, type) {
            const url = "{{ route('tips.like', [':id']) }}";
            $.get(url.replace(':id', tipId) + '?type=' + type).then(function () {
                $('#likeTip-' + tipId).parent().remove();
            });
        }
    </script>
@stop

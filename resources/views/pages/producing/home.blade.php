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
                        <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px;"
                             role="alert">
                            
                            <?php
                            /** @var bool $itemExists */
                            ?>

                            @if ($itemExists)
                                <span title="{{ __('saved_learning_items.saved') }}" class="save_tip">
                                    <img class="save_tip_icon" src="{{ URL::asset('assets/img/opgeslagen_icon_wit.svg', true) }}"/></span>
                            @else
                                <a title="{{ __('saved_learning_items.save') }}"  class="save_tip" href="{{ route('saved-learning-item-create', ['category' => 'tip', 'item_id' => $evaluatedTip->getTip()->id]) }}">
                                    <img class="save_tip_icon" src="{{ URL::asset('assets/img/opgeslagen-niet-ingevuld.svg', true) }}"/></a>
                            @endif

                            <h4>{{ __('tips.personal-tip') }}</h4>
                            <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
                            <br/>

                            <p class="text-right">
                                <a class="alert-link" style="color: white;"
                                   href="{{ route('analysis-producing-detail', ['year' => 'all', 'month' => 'all']) }}">{{ __('tips.see-more') }}</a>
                            </p>
                        </div>
                    @endif

                    <p>{{ __('home.welcome-student') }}
                        <br /><br />{{ __('home.see-menu') }}</p>
                    <ul>
                        <li>{{ __('home.with-tile') }} <b>{{ __('home.learningprocess') }}</b> {{ __('home.steps.1') }}</li>
                        <li>{{ __('home.with-tile') }} <b>{{ __('home.progress') }}</b> {{ __('home.steps.2') }}</li>
                        <li>{{ __('home.with-tile') }} <b>{{ __('home.analysis') }}</b> {{ __('home.steps.3') }}</li>
                        <li>{{ __('home.with-tile') }} <b>{{ __('home.deadlines') }}</b> {{ __('home.steps.4') }}</li>
                        <li>{{ __('home.with-tile') }} <b>{{ __('home.profile') }}</b> {{ __('home.steps.5') }}</li>
                    </ul>
                    <p>{{ __('home.goodluck') }}</p>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    @if(Auth::user()->hasCurrentWorkplaceLearningPeriod() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                        <h1>{{ __('dashboard.lastWZHtitle') }}</h1>
                        @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(5) as $activity)
                            <div class="dash-bar">
                                <?php
                                $fmt = new IntlDateFormatter(
                                        (app()->getLocale() == 'en') ? 'en_US' : 'nl_NL',
                                        IntlDateFormatter::GREGORIAN,
                                        IntlDateFormatter::NONE,
                                        null,
                                        null,
                                        'EEEE dd-MM'
                                );
                                ?>
                                <div class="dash-date">
                                    {{ucwords($fmt->format(strtotime($activity->date))) }}
                                </div>
                                <div class="dash-description">
                                    <b>{{ $activity->description }}</b>
                                </div>
                                <div class="dash-hours">
                                    <strong>
                                        @if($activity->duration < 1)
                                            ({{ round($activity->duration * 60) }} {{ __('minutes') }})
                                        @else
                                            ({{ $activity->duration }} {{ __('hours') }})
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>


            <br /><a href="{{ '/bugreport' }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> {{ __('home.tips') }}</a>

        </div>
@stop

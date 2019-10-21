<?php
/**
 * This file (feedback.blade.php) was created on 08/05/2016 at 01:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

/** @var App\LearningActivityProducing $lap */
/** @var App\Feedback $feedback */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('activity.feedback.feedback') }}
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                $("#expand-toggle").hide();
                $(".cond-hidden").hide();
                $("[name='support_requested']").click(function () {
                    if ($(this).val() == "0") {
                        $("#expand-toggle").hide();
                    } else {
                        $("#expand-toggle").show();
                    }
                });
                $(".expand-click").click(function () {
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                });
                $("[name='support_requested']:checked").trigger("click");
                //$(".expand-click > input").trigger("click");

                <?php
                $notFinishedOther = $feedback->notfinished !== 'Geen/Weinig Ervaring' && $feedback->notfinished !== 'Geen Hulpbron beschikbaar' && $feedback->notfinished !== 'Tijdgebrek';
                ?>

                @if($notFinishedOther)
                        $('.cond-hidden[name="newnotfinished"]').show();
                @endif

            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <h2>{{ __('activity.activity') }}</h2>
                <p>{{ __('activity.feedback.difficulty-indication') }}
                    <strong>{{ __($lap->difficulty->difficulty_label) }}</strong>.</p>
                {{ __('activity.feedback.given-information') }}
                <ul>
                    <li>{{ __('elements.calendar.labels.date') }}/{{ __('activity.feedback.time') }}
                        : {{ date('d-m', strtotime($lap->date)) }}, {{ __('activity.feedback.duration') }}
                        <strong>
                            @if($lap->duration < 1)
                                {{ round($lap->duration * 60) }} {{ __('minutes') }}
                            @else
                                {{ $lap->duration }} {{ __('hours') }}
                            @endif
                        </strong>.
                    </li>
                    <li>{{ __('activity.category') }} <strong>{{ $lap->category->localizedLabel() }}</strong>,
                        {{ $lap->status->isBusy() ? __('activity.feedback.busy') : __('activity.feedback.finished') }}
                    </li>
                    <li>{{ !$lap->resourcePerson ? __('activity.feedback.not-used-source') : __('activity.feedback.used-source') }}
                        @if($lap->resourceMaterial)
                            ({{ __($lap->resourceMaterial->rm_label) }}
                            : {{ $lap->res_material_detail }})
                        @elseif($lap->resourcePerson)
                            ({{ __('activity.producing.person') }}
                            : {{ $lap->resourcePerson->localizedLabel()  }})
                        @else
                            ({{ __('activity.alone')  }})
                        @endif

                    </li>
                </ul>
            </div>
        </div>

        {!! Form::open(array('id' => 'feedbackForm', 'url' => route('feedback-producing-update', ['id' => $feedback->fb_id]))) !!}
        <div class="row well">
            <h2>{{ __('activity.feedback.lookback') }}</h2>

            <div class="col-md-2 form-group buttons">
                <h4>{{ __('activity.feedback.why-hard') }}</h4>
                <label>
                    <input type="radio" name="notfinished"
                           value="Geen/Weinig Ervaring"
                           @if($feedback->notfinished === 'Geen/Weinig Ervaring' || empty($feedback->notfinished)) checked @endif
                    />
                    <span>{{ __('activity.none') }}/{{ __('activity.feedback.little-experience') }}</span>
                </label>

                <label>
                    <input type="radio" name="notfinished"
                           value="Geen Hulpbron beschikbaar"
                           @if($feedback->notfinished === 'Geen Hulpbron beschikbaar') checked @endif
                    />
                    <span>{{ __('activity.feedback.no-aid-source') }}</span>
                </label>
                <label>
                    <input type="radio" name="notfinished"
                           value="Tijdgebrek"
                           @if($feedback->notfinished === 'Tijdgebrek') checked @endif
                    />
                    <span>{{ __('activity.feedback.lack-of-time') }}</span>
                </label>



                <label class="expand-click">
                    <input type="radio" name="notfinished" value="Anders"
                           @if($notFinishedOther) checked @endif
                    />
                    <span class="new">{{ __('activity.other') }} ({{ __('activity.add') }})</span>
                </label>
                <input class="cond-hidden form-control" type="text"
                       name="newnotfinished"
                       placeholder="{{ __('activity.description') }}"
                       maxlength="80"
                       value="{{ $notFinishedOther ? $feedback->notfinished : '' }}"
                />

            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ __('activity.feedback.how-help') }}</h4>
                <label>
                    <input type="radio" name="support_requested"
                           value="0"
                           @if($feedback->support_requested === 0 || !$feedback->isSaved()) checked @endif
                    />
                    <span>{{ __('activity.feedback.no-help') }}</span>
                </label>
                <label>
                    <input type="radio" name="support_requested"
                           value="1"
                           @if($feedback->support_requested === 1) checked @endif
                    />
                    <span>{{ __('activity.feedback.help-received') }}</span>
                </label>
                <label>
                    <input type="radio" name="support_requested"
                           value="2"
                           @if($feedback->support_requested === 2) checked @endif
                    />
                    <span>{{ __('activity.feedback.help-asked') }}</span>
                </label>
            </div>
            <div id="expand-toggle" class="col-md-3 form-group" style="display: none">
                <h4>{{ __('activity.feedback.help-received-wp') }}</h4>
                <textarea class="form-control fit-bs"
                          name="supported_provided_wp"
                          maxlength="1000"
                          placeholder="{{ __('activity.feedback.help-received-example') }}" rows="8"
                          cols="40">{{ $feedback->supported_provided_wp }}</textarea>
            </div>

            <div class="col-md-2 form-group buttons">
                <h4>{{ __('activity.feedback.happy-with-progress') }}</h4>
                <label>
                    <input type="radio" name="progress_satisfied"
                           value="2"
                           @if($feedback->progress_satisfied === 2 || !$feedback->isSaved()) checked @endif
                    />
                    <span>{{ __('general.yes') }}</span>
                </label>
                <label>
                    <input type="radio" name="progress_satisfied"
                           value="1"
                           @if($feedback->progress_satisfied === 1) checked @endif
                    />
                    <span>{{ __('general.no') }}</span>
                </label>
            </div>

            <div class="col-md-3 form-group">
                <h4>{{ __('activity.feedback.own-initiative') }}</h4>
                <textarea class="form-control fit-bs"
                          name="initiatief"
                          maxlength="1000"
                          placeholder="{{ __('activity.feedback.initiative-explanation') }}" rows="8"
                          cols="40">{{ $feedback->initiative }}</textarea>
            </div>

        </div>

        <div class="row form-horizontal well" style="min-height: 350px;">
            <h2>{{ __('activity.feedback.preview') }}</h2>
            <div class="col-sm-4 form-group">
                <h4>{{ __('activity.feedback.next-steps') }}</h4>

                <textarea class="form-control fit-bs"
                          name="vervolgstap_zelf"
                          maxlength="1000"
                          placeholder="{{ __('activity.feedback.which-person') }}" rows="8"
                          cols="40">{{ $feedback->nextstep_self }}</textarea>
            </div>
            <div class="col-sm-4 form-group">
                <h4>{{ __('activity.feedback.help-needed') }}</h4>
                <textarea class="form-control fit-bs"
                          name="ondersteuning_werkplek"
                          maxlength="1000"
                          placeholder="{{ __('activity.feedback.help-needed-example') }}" rows="8"
                          cols="40">{{ $feedback->support_needed_wp }}</textarea>
                <br/>
                <input type="checkbox" name="ondersteuningWerkplek"
                       value="Geen"
                       @if($feedback->support_needed_wp === 'Geen') checked @endif/> {{ __('activity.feedback.no-help-needed') }}
                <br style="clear: both;"/>
            </div>
            <div class="col-sm-4 form-group">
                <h4>{{ __('activity.feedback.help-school-needed') }}</h4>
                <textarea class="form-control fit-bs"
                          name="ondersteuning_opleiding"
                          maxlength="1000"
                          placeholder="{{ __('activity.feedback.help-school-needed-example') }}" rows="8"
                          cols="40">{{ $feedback->support_needed_ed }}</textarea>
                <br/>
                <input type="checkbox" name="ondersteuningOpleiding"
                       value="Geen"
                       @if($feedback->support_needed_ed === 'Geen') checked @endif/> {{ __('activity.feedback.no-school-help-needed') }}
                <br style="clear: both;"/>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <input type="submit" class="btn btn-info" value="{{ __('general.save') }}"/>
            </div>
        </div>

        {{ Form::close() }}
    </div>
@stop

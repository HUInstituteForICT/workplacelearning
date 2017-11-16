<?php
/**
 * This file (feedback.blade.php) was created on 08/05/2016 at 01:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('activity.feedback.feedback') }}
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
            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <h2>{{ Lang::get('activity.activity') }}</h2>
                <p>{{ Lang::get('activity.feedback.difficulty-indication') }} <b>{{ $lap->getDifficulty() }}</b>.</p>
                {{ Lang::get('activity.feedback.given-information') }}
                <ul>
                    <li>{{ Lang::get('elements.calendar.labels.date') }}/{{ Lang::get('activity.feedback.time') }}
                        : {{ date('d-m', strtotime($lap->date)) }}, {{ Lang::get('activity.feedback.duration') }}
                        <b>{{ $lap->getDurationString() }}</b>.
                    </li>
                    <li>{{ Lang::get('activity.category') }} <b>{{ $lap->getCategory() }}</b>,
                        {{ ($lap->getStatus() == "Mee Bezig") ? Lang::get('activity.feedback.busy') : Lang::get('activity.feedback.finished') }}
                    </li>
                    <li>{{ (is_null($lap->res_person_id)) ? Lang::get('activity.feedback.not-used-source') : Lang::get('activity.feedback.used-source') }}
                        ({{ $lap->getResourceDetail() }})
                    </li>
                </ul>
            </div>
        </div>

        {!! Form::open(array('id' => 'feedbackForm', 'url' => route('feedback-producing-update', ['id' => $fb->fb_id]))) !!}
        <div class="row well">
            <h2>{{ Lang::get('activity.feedback.lookback') }}</h2>

            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.feedback.why-hard') }}</h4>
                <label><input type="radio" name="notfinished"
                              value="Geen/Weinig Ervaring" {{ ($fb->isSaved()) ? (($fb->notfinished == "Geen/Weinig Ervaring") ? "checked" : "") : "checked" }}/><span>{{ Lang::get('activity.none') }}
                        /{{ Lang::get('activity.feedback.little-experience') }}</span></label>
                <label><input type="radio" name="notfinished"
                              value="Geen Hulpbron beschikbaar" {{ ($fb->isSaved() && $fb->notfinished == "Geen Hulpbron beschikbaar") ? "checked" : "" }}/><span>{{ Lang::get('activity.feedback.no-aid-source') }}</span></label>
                <label><input type="radio" name="notfinished"
                              value="Tijdgebrek" {{ ($fb->isSaved() && $fb->notfinished == "Tijdgebrek") ? "checked" : "" }}/><span>{{ Lang::get('activity.feedback.lack-of-time') }}</span></label>
                <label class="expand-click"><input type="radio" name="notfinished" value="Anders" {{
                    ($fb->isSaved() && $fb->notfinished == "Anders") ? "checked" : ""
                    }}/><span class="new">{{ Lang::get('activity.other') }} ({{ Lang::get('activity.add') }}
                        )</span></label>
                <input {!! ($fb->isSaved()) ? "disabled " : "" !!}class="cond-hidden" type="text" name="newnotfinished"
                       placeholder="{{ Lang::get('activity.description') }}"
                       maxlength="80"
                        {!!
                        ($fb->isSaved() && $fb->notfinished != "Geen/Weinig Ervaring" && $fb->notfinished != "Geen Hulpbron beschikbaar" && $fb->notfinished != "Tijdgebrek") ? "value=\"".$fb->notfinished."\" disabled" : ""
                        !!} />

            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.feedback.how-help') }}</h4>
                <label><input type="radio" name="support_requested"
                              value="0" {{ ($fb->isSaved()) ? (($fb->support_requested == "0") ? "checked   " : "") : "checked" }}/><span>{{ Lang::get('activity.feedback.no-help') }}</span></label>
                <label><input type="radio" name="support_requested"
                              value="1" {{ ($fb->isSaved() && $fb->support_requested == "1") ? "checked" : "" }}/><span>{{ Lang::get('activity.feedback.help-received') }}</span></label>
                <label><input type="radio" name="support_requested"
                              value="2" {{ ($fb->isSaved() && $fb->support_requested == "2") ? "checked" : "" }}/><span>{{ Lang::get('activity.feedback.help-asked') }}</span></label>
            </div>
            <div id="expand-toggle" class="col-md-3 form-group">
                <h4>{{ Lang::get('activity.feedback.help-received-wp') }}</h4>
                <textarea class="form-control fit-bs"
                          {!! ($fb->isSaved()) ? "disabled " : "" !!}name="supported_provided_wp"
                          maxlength="150"
                          placeholder="{{ Lang::get('activity.feedback.help-received-example') }}" rows="8"
                          cols="40">{{ ($fb->isSaved()) ? $fb->supported_provided_wp : "" }}</textarea>
            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.feedback.happy-with-progress') }}</h4>
                <label><input type="radio" name="progress_satisfied"
                              value="2" {{ ($fb->isSaved()) ? (($fb->progress_satisfied == "2") ? "checked" : "") : "checked" }}/><span>{{ Lang::get('general.yes') }}</span></label>
                <label><input type="radio" name="progress_satisfied"
                              value="1" {{ ($fb->isSaved() && $fb->progress_satisfied == "1") ? "checked" : "" }}/><span>{{ Lang::get('general.no') }}</span></label>
            </div>
            <div class="col-md-3 form-group">
                <h4>{{ Lang::get('activity.feedback.own-initiative') }}</h4>
                <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="initiatief"
                          maxlength="150"
                          placeholder="{{ Lang::get('activity.feedback.initiative-explanation') }}" rows="8"
                          cols="40">{{ ($fb->isSaved()) ? $fb->initiative : "" }}</textarea>
            </div>

        </div>

        <div class="row form-horizontal well" style="min-height: 350px;">
            <h2>{{ Lang::get('activity.feedback.preview') }}</h2>
            <div class="col-sm-4 form-group">
                <h4>{{ Lang::get('activity.feedback.next-steps') }}</h4>

                <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="vervolgstap_zelf"
                          maxlength="150"
                          placeholder="{{ Lang::get('activity.feedback.which-person') }}" rows="8"
                          cols="40">{{ ($fb->isSaved()) ? $fb->nextstep_self : "" }}</textarea>
            </div>
            <div class="col-sm-4 form-group">
                <h4>{{ Lang::get('activity.feedback.help-needed') }}</h4>
                <textarea class="form-control fit-bs"
                          {!! ($fb->isSaved()) ? "disabled " : "" !!}name="ondersteuning_werkplek"
                          maxlength="150"
                          placeholder="{{ Lang::get('activity.feedback.help-needed-example') }}" rows="8"
                          cols="40">{{ ($fb->isSaved()) ? $fb->support_needed_wp : "" }}</textarea>
                <br/><input type="checkbox" name="ondersteuningWerkplek"
                            value="Geen" {{ ($fb->isSaved() && $fb->support_needed_wp == "Geen") ? "checked" : "" }}/> {{ Lang::get('activity.feedback.no-help-needed') }}
                <br style="clear: both;"/>
            </div>
            <div class="col-sm-4 form-group">
                <h4>{{ Lang::get('activity.feedback.help-school-needed') }}</h4>
                <textarea class="form-control fit-bs"
                          {!! ($fb->isSaved()) ? "disabled " : "" !!}name="ondersteuning_opleiding"
                          maxlength="150"
                          placeholder="{{ Lang::get('activity.feedback.help-school-needed-example') }}" rows="8"
                          cols="40">{{ ($fb->isSaved()) ? $fb->support_needed_ed : "" }}</textarea>
                <br/><input type="checkbox" name="ondersteuningOpleiding"
                            value="Geen" {{ ($fb->isSaved() && $fb->support_needed_ed == "Geen") ? "checked" : "" }}/> {{ Lang::get('activity.feedback.no-school-help-needed') }}
                <br style="clear: both;"/>

            </div>
        </div>
        @if(!$fb->isSaved())
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-info" value="{{ Lang::get('general.save') }}"/>
                </div>
            </div>
        @endif

        {{ Form::close() }}
    </div>
@stop

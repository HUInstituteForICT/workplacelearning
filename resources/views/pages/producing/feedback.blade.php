<?php
/**
 * This file (feedback.blade.php) was created on 08/05/2016 at 01:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Feedback
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function(){
                $("#expand-toggle").hide();
                $(".cond-hidden").hide();
                $("[name='support_requested']").click(function(){
                    if($(this).val() == "0") {
                        $("#expand-toggle").hide();
                    } else {
                        $("#expand-toggle").show();
                    }
                });
                $(".expand-click").click(function(){
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                });
                $("[name='support_requested']:checked").trigger("click");
                //$(".expand-click > input").trigger("click");
            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <h2>Activiteit</h2>
                <p>Je hebt aangegeven dat je deze activiteit <b>{{ $lap->getDifficulty() }}</b> vond.</p>
                Je hebt de volgende informatie ingegeven:
                <ul>
                    <li>Datum/Tijd: {{ date('d-m', strtotime($lap->date)) }}, de activiteit duurde <b>{{ $lap->getDurationString() }}</b>.</li>
                    <li>De activiteit viel in de categorie <b>{{ $lap->getCategory() }}</b>, en je {{ ($lap->getStatus() == "Mee Bezig") ? "bent hier nog" : "hebt deze" }} <b>{{ $lap->getStatus() }}</b>.</li>
                    <li>Je hebt tijdens het werken aan deze bron {{ (is_null($lap->res_person_id)) ? "g" : "" }}een hulpbron aangeboord. ({{ $lap->getResourceDetail() }})</li>
                </ul>
            </div>
        </div>

        {!! Form::open(array('id' => 'feedbackForm', 'url' => route('feedback-producing-update', ['id' => $fb->fb_id]))) !!}
        <div class="row">
            <div class="form-horizontal well" style="min-height: 370px;">
                <h2>Terugblik</h2>
                <div class="col-md-2 form-group buttons">
                    <h4>Wat maakte deze activiteit voor jou moeilijk?</h4>
                    <label><input type="radio" name="notfinished" value="Geen/Weinig Ervaring" {{ ($fb->isSaved()) ? (($fb->notfinished == "Geen/Weinig Ervaring") ? "checked" : "") : "checked" }}/><span>Geen/Weinig Ervaring</span></label>
                    <label><input type="radio" name="notfinished" value="Geen Hulpbron beschikbaar" {{ ($fb->isSaved() && $fb->notfinished == "Geen Hulpbron beschikbaar") ? "checked" : "" }}/><span>Geen hulpbron beschikbaar</span></label>
                    <label><input type="radio" name="notfinished" value="Tijdgebrek" {{ ($fb->isSaved() && $fb->notfinished == "Tijdgebrek") ? "checked" : "" }}/><span>Tijdgebrek</span></label>
                    <label class="expand-click"><input type="radio" name="notfinished" value="Anders" {{
                    ($fb->isSaved() && $fb->notfinished == "Anders") ? "checked" : ""
                    }}/><span class="new">Anders (Toevoegen)</span></label>
                    <input {!! ($fb->isSaved()) ? "disabled " : "" !!}class="cond-hidden" type="text" name="newnotfinished" placeholder="Omschrijving"
                           maxlength="80"
                            {!!
                            ($fb->isSaved() && $fb->notfinished != "Geen/Weinig Ervaring" && $fb->notfinished != "Geen Hulpbron beschikbaar" && $fb->notfinished != "Tijdgebrek") ? "value=\"".$fb->notfinished."\" disabled" : ""
                            !!} />

                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Hoe heb je hulp gekregen bij deze activiteit?</h4>
                    <label><input type="radio" name="support_requested" value="0" {{ ($fb->isSaved()) ? (($fb->support_requested == "0") ? "checked   " : "") : "checked" }}/><span>Geen Hulp gekregen</span></label>
                    <label><input type="radio" name="support_requested" value="1" {{ ($fb->isSaved() && $fb->support_requested == "1") ? "checked" : "" }}/><span>Hulp gekregen</span></label>
                    <label><input type="radio" name="support_requested" value="2" {{ ($fb->isSaved() && $fb->support_requested == "2") ? "checked" : "" }}/><span>Hulp gevraagd</span></label>
                </div>
                <div id="expand-toggle" class="col-md-3 form-group">
                    <h4>Welke hulp heb je gekregen van je werkplek?</h4>
                    <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="supported_provided_wp"
                              maxlength="150"
                              placeholder="Bijvoorbeeld: Een collega heeft mij een aangeraden om een boek na te slaan." rows="8" cols="40">{{ ($fb->isSaved()) ? $fb->supported_provided_wp : "" }}</textarea>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Ben je tevreden met de voortgang?</h4>
                    <label><input type="radio" name="progress_satisfied" value="2" {{ ($fb->isSaved()) ? (($fb->progress_satisfied == "2") ? "checked" : "") : "checked" }}/><span>Ja</span></label>
                    <label><input type="radio" name="progress_satisfied" value="1" {{ ($fb->isSaved() && $fb->progress_satisfied == "1") ? "checked" : "" }}/><span>Nee</span></label>
                </div>
                <div class="col-md-3 form-group">
                    <h4>Welk eigen initiatief heb je genomen?</h4>
                    <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="initiatief"
                              maxlength="150"
                              placeholder="Leg in je eigen woorden uit wat je hebt gedaan om verder te komen" rows="8" cols="40" >{{ ($fb->isSaved()) ? $fb->initiative : "" }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal well" style="min-height: 350px;">
                <h2>Vooruitblik</h2>
                <div class="col-sm-4 form-group">
                    <h4>Welke vervolgstap wil je zelf nemen?</h4>
                    <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="vervolgstap_zelf"
                              maxlength="150"
                              placeholder="Welke persoon/bron kun je raadplegen?" rows="8" cols="40">{{ ($fb->isSaved()) ? $fb->nextstep_self : "" }}</textarea>
                </div>
                <div class="col-sm-4 form-group">
                    <h4>Welke ondersteuning heb je daarbij nodig van je werkplek?</h4>
                    <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="ondersteuning_werkplek"
                              maxlength="150"
                              placeholder="Bijvoorbeeld: Een collega vragen om mee te kijken." rows="8" cols="40">{{ ($fb->isSaved()) ? $fb->support_needed_wp : "" }}</textarea>
                    <br /><input type="checkbox" name="ondersteuningWerkplek" value="Geen" {{ ($fb->isSaved() && $fb->support_needed_wp == "Geen") ? "checked" : "" }}/> Ik heb geen ondersteuning nodig van mijn werkplek
                    <br style="clear: both;" />
                </div>
                <div class="col-sm-4 form-group">
                    <h4>Welke ondersteuning heb je nodig vanuit je opleiding?</h4>
                    <textarea class="form-control fit-bs" {!! ($fb->isSaved()) ? "disabled " : "" !!}name="ondersteuning_opleiding"
                              maxlength="150"
                              placeholder="Bijvoorbeeld: Contact leggen met mijn vakdocent/stagebegeleider." rows="8" cols="40">{{ ($fb->isSaved()) ? $fb->support_needed_ed : "" }}</textarea>
                    <br /><input type="checkbox" name="ondersteuningOpleiding" value="Geen" {{ ($fb->isSaved() && $fb->support_needed_ed == "Geen") ? "checked" : "" }}/> Ik heb geen ondersteuning nodig van mijn opleiding
                    <br style="clear: both;" />
                </div>
        </div>
        @if(!$fb->isSaved())
        <div class="row">
            <div class="col-sm-12 form-group">
                <input type="submit" class="btn btn-info" value="Opslaan" />
            </div>
        </div>
        @endif

        {{ Form::close() }}
    </div>
@stop

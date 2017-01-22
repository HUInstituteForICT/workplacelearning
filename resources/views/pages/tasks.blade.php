<?php
/**
 * This file (tasks.blade.php) was created on 06/24/2016 at 15:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function() {
                $("#swv_id").on('change', function(){
                    if($(this).val() == "new" && $(this).is(":visible")){
                        $("#cond-select-hidden").show();
                    } else {
                        $("#cond-select-hidden").hide();
                    }
                });
                $(".expand-click").click(function(){
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                    $("#cond-select-hidden").hide();
                    $("#swv_id").trigger("change");
                });
                $("#help-click").click(function(){
                    $('#help-text').slideToggle('slow');
                });
                $(".cond-hidden").hide();
                $("#cond-select-hidden").hide();
                $("#category").hide();
                $("#help-text").hide();
                $(".expand-click :input[value='persoon']").click();
                $("#newcat").click(function(){
                    $("#category").show();
                })

                $('.input-group.date').datepicker({
                    daysOfWeekDisabled: "0,6",
                    todayHighlight: true,
                    endDate: "0d"
                });

            });
        </script>
        <div class="row">
            <div class="col-md-12 well">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u">Hoe werkt deze pagina?</h4>
                <div id="help-text">
                    <ol>
                        <li>Kies een datum waarop je de werkzaamheid hebt uitgevoerd. Deze mag alleen in het verleden of heden liggen.</li>
                        <li>Vul een omschrijving in van wat je hebt gedaan</li>
                        <li>Selecteer hoe je aan deze taak hebt gewerkt, of vul een nieuw verband toe. Heb je er alleen aan gewerkt of samen met iemand?</li>
                        <li>Selecteer de status van deze werkzaamheid. Is deze al afgerond of ben je er nog mee bezig?</li>
                        <li>Selecteer hoe moeilijk je deze taak vond. Liep je tegen problemen aan of ging het je goed af?</li>
                        <li>Klik op 'Opslaan'. De taak wordt onder in het scherm toegevoegd.</li>
                    </ol>
                </div>
            </div>
        </div>
        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() == NULL)
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-notice">
                        <span>{{ Lang::get('elements.alerts.notice') }}: </span>{!! str_replace('%s', LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "https://werkplekleren.hu.nl/stageperiode/edit/0", array()), Lang::get('dashboard.nointernshipactive')) !!}
                    </div>
                </div>
            </div>
        @endif
        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            {!! Form::open(array('id' => 'taskForm', 'class' => 'form-horizontal well', 'url' => URL::to('leerproces/create', array(), true))) !!}
                <div class="col-md-2 form-group">
                    <h4>Activiteit</h4>
                    <div class="input-group date">
                        <input type="text" name="datum" value="{{ date('m/d/Y', strtotime("now")) }}" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>

                    <h5>Omschrijving:</h5>
                    <textarea class="form-control fit-bs" name="omschrijving" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19"></textarea>

                    <h5>Koppel aan vorige activiteit:</h5>
                    <select class="form-control fit-bs" name="previous_wzh" >
                        <option value="-1">- Niet Koppelen-</option>
                        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != NULL)
                            @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getUnfinishedActivityProducing() as $w)
                                <option value="{{ $w->wzh_id }}">{{ date('d-m', strtotime($w->wzh_datum)) ." - ".$w->wzh_omschrijving }}</option>
                            @endforeach
                        @endif
                    </select>

                </div>
                <div class="col-md-2 form-group buttons numpad">
                    <h4>Uren</h4>
                    <label><input type="radio" name="aantaluren" value="0.25" checked><span>15 min.</span></label>
                    <label><input type="radio" name="aantaluren" value="0.50"><span>30 min.</span></label>
                    <label><input type="radio" name="aantaluren" value="0.75"><span>45 min.</span></label>
                    @for($i = 1; $i <= 6; $i++)
                        {!! "<label>". Form::radio('aantaluren', $i) ."<span>". $i ." ". Lang::choice('elements.tasks.hour', $i) ."</span></label>" !!}
                    @endfor
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Categorie</h4>
                    @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null)
                        @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getCategories() as $cat)
                            <label><input type="radio" name="category_id" value="{{ $cat->category_id }}" {{ ($cat->cg_id == 1) ? "checked" : "" }}/><span>{{ $cat->cg_value }}</span></label>
                        @endforeach
                    @endif
                    <div>
                        <label class="newcat"><input type="radio" name="cat_id" value="new" /><span class="new" id="newcat">Anders<br />(Toevoegen)</span></label>
                        <input id="category" type="text" oninput="this.setCustomValidity('')" pattern="[0-9a-zA-Z ()]{1,50}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z ()')" name="newcat" placeholder="Omschrijving" />
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Werken/Leren Met</h4>
                    <div id="swvcontainer">
                        <label class="expand-click"><input type="radio" name="lerenmet" value="persoon" checked/><span>Persoon</span></label>
                        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null)
                            <select id="swv_id" name="swv_id" class="cond-hidden">
                            @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcesPerson() as $swv)
                                <option value="{{ $swv->swv_id }}">{{ $swv->person_label }}</option>
                            @endforeach */ ?>
                                <option value="new">Nieuw/Anders</option>
                            </select>
                            <input id="cond-select-hidden" type="text" oninput="this.setCustomValidity('')" pattern="[0-9a-zA-Z ()]{1,50}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z ()')" name="newswv" placeholder="Omschrijving" />
                        @endif
                    </div>
                    <div id="solocontainer">
                        <label class="expand-click"><input type="radio" name="lerenmet" value="alleen" /><span>Alleen</span></label>
                    </div>
                    <div id="internetcontainer">
                        <label class="expand-click"><input type="radio" name="lerenmet" value="internet" /><span>Internetbron</span></label>
                        <input class="cond-hidden" type="text" name="internetsource" value="" placeholder="http://www.bron.domein/" />
                    </div>
                    <div id="boekcontainer">
                        <label class="expand-click"><input type="radio" name="lerenmet" value="boek" /><span>Boek/Artikel</span></label>
                        <input class="cond-hidden" type="text" name="booksource" value="" placeholder="Naam Boek/Artikel" />
                    </div>
                    <div id="newcontainer">
                        <label class="expand-click"><input type="radio" name="lerenmet" value="new" /><span class="new" id="newswv">Anders<br />(Toevoegen)</span></label>
                        <input class="cond-hidden" type="text" name="newlerenmet" placeholder="Omschrijving" />
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Status</h4>
                    <label><input type="radio" name="status" value="1" checked/><span>Afgerond</span></label>
                    <label><input type="radio" name="status" value="2"/><span>Mee Bezig</span></label>
                    <label><input type="radio" name="status" value="3"/><span>Overgedragen</span></label>
                </div>
                <div class="col-md-1 form-group buttons">
                    <h4>Moeilijkheidsgraad</h4>
                    <label><input type="radio" name="moeilijkheid" value="1" checked/><span>Makkelijk</span></label>
                    <label><input type="radio" name="moeilijkheid" value="2"/><span>Gemiddeld</span></label>
                    <label><input type="radio" name="moeilijkheid" value="3"/><span>Moeilijk</span></label>
                </div>
                <div class="col-md-1 form-group buttons">
                    <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;" value="Save" />
                </div>
            {{ Form::close() }}
        </div>
        <div class="row">
            <table class="table blockTable col-md-12">
                <thead class="blue_tile">
                <tr>
                    <td>Datum</td>
                    <td>Omschrijving</td>
                    <td>Tijd (Uren)</td>
                    <td>Werken/leren met</td>
                    <td>Complexiteit</td>
                </tr>
                </thead>
                @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                    @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastWerkzaamheden(8) as $wzh)
                        <tr>
                            <td>{{ date('d-m', strtotime($wzh->wzh_datum)) }}</td>
                            <td>{{ $wzh->wzh_omschrijving }}</td>
                            <td>{{ $wzh->wzh_aantaluren ." ". Lang::choice('dashboard.hours', $wzh['hours']) }}</td>
                            <td>{{ ucwords($wzh->lerenmet) . (($wzh->lerenmetdetail != null) ? ": ".$wzh->getlerenmetdetail() : "") }}</td>
                            <td>{{ $wzh->getMoeilijkheid() }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

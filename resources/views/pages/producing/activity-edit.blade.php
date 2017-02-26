@extends('layout.HUdefault')
@section('title')
    Activiteiten - Edit
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function() {
                // Tooltips
                (function() {
                    $('[data-toggle="tooltip"]').tooltip();
                })();

                // Resource person
                (function() {
                    $(".expand-click").click(function(){
                        $(".cond-hidden").hide();
                        $(this).siblings().show();
                        $("#cond-select-hidden").hide();
                        $("#rp_id").trigger("change");
                    });

                    $("#rp_id").on('change', function(){
                        if($(this).val() == "new" && $(this).is(":visible")){
                            $("#cond-select-hidden").show();
                        } else {
                            $("#cond-select-hidden").hide();
                        }
                    });

                    $(".cond-hidden").hide();
                    $("#cond-select-hidden").hide();
                    $("#category").hide();

                    $(".expand-click :input[value='persoon']").click();
                    $("#newcat").click(function(){
                        $("#category").show();
                    });
                })();
            });
        </script>
        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif
        {{ Form::open(array('url' => route('process-producing-update', ['id' => $activity->lap_id]), 'class' => 'form-horizontal')) }}
            <div class="row well">
                <div class="col-md-2 form-group">
                    <h4>Activiteit</h4>
                    <input class="form-control fit-bs" type="date" name="datum" value="{{ (count($errors) > 0) ? old('date') : $activity->date }}" /><br/>
                    <h5>Omschrijving:</h5>
                    <textarea class="form-control fit-bs" name="omschrijving" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\/"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ (count($errors) > 0) ? old('description') : $activity->description }}</textarea>
                </div>
                <div class="col-md-2 form-group buttons numpad">
                    <h4>Uren</h4>
                    <label><input type="radio" name="aantaluren" value="0.25" {{ (old('aantaluren') == $activity->duration) ? 'checked' : ($activity->duration == 0.25) ? 'checked' : null }}><span>15 min.</span></label>
                    <label><input type="radio" name="aantaluren" value="0.50" {{ (old('aantaluren') == $activity->duration) ? 'checked' : ($activity->duration == 0.50) ? 'checked' : null }}><span>30 min.</span></label>
                    <label><input type="radio" name="aantaluren" value="0.75" {{ (old('aantaluren') == $activity->duration) ? 'checked' : ($activity->duration == 0.75) ? 'checked' : null }}><span>45 min.</span></label>
                    @for($i = 1; $i <= 6; $i++)
                        <label><input type="radio" name = "aantaluren" value="{{ $i }}" {{ (old('aantaluren') == $activity->duration) ? 'checked' : ($activity->duration == $i) ? 'checked' : null }}><span>{{ $i . ' ' . Lang::choice('elements.tasks.hour', $i) }}</span></label>
                    @endfor
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Categorie <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.producing_category') }}"></i></h4>
                    @foreach($categories as $key => $value)
                        <label><input type="radio" name="category_id" value="{{ $value->category_id }}" {{ (old('category_id') == $value->category_id) ? 'checked' : ($activity->category_id == $value->category_id) ? 'checked' : null }}/><span>{{ $value->category_label }}</span></label>
                    @endforeach
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Werken/Leren Met <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.producing_with') }}"></i></h4>
                    <div id="swvcontainer">
                        <label class="expand-click"><input type="radio" name="resource" value="persoon" checked/><span>Persoon</span></label>
                        <select id="rp_id" name="personsource" class="cond-hidden">
                            @foreach($learningWith as $key => $value)
                                <option value="{{ $value->rp_id }}" {{ (old('personsource') == $value->rp_id) ? 'checked' : ($activity->res_person_id == $value->res_person_id) ? 'checked' : null }}>{{ $value->person_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="solocontainer">
                        <label class="expand-click"><input type="radio" name="resource" value="alleen" /><span>Alleen</span></label>
                    </div>
                    <div id="internetcontainer">
                        <label class="expand-click"><input type="radio" name="resource" value="internet" /><span>Internetbron</span></label>
                        <input class="cond-hidden" type="text" name="internetsource" value="" placeholder="http://www.bron.domein/" />
                    </div>
                    <div id="boekcontainer">
                        <label class="expand-click"><input type="radio" name="resource" value="boek" /><span>Boek/Artikel</span></label>
                        <input class="cond-hidden" type="text" name="booksource" value="" placeholder="Naam Boek/Artikel" />
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Status <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.producing_status') }}"></i></h4>
                    <label><input type="radio" name="status" value="1" {{ (old('status') == 1) ? 'checked' : ($activity->status_id == 1) ? 'checked' : null }} /><span>Afgerond</span></label>
                    <label><input type="radio" name="status" value="2" {{ (old('status') == 2) ? 'checked' : ($activity->status_id == 2) ? 'checked' : null }} /><span>Mee Bezig</span></label>
                    <label><input type="radio" name="status" value="3" {{ (old('status') == 3) ? 'checked' : ($activity->status_id == 3) ? 'checked' : null }} /><span>Overgedragen</span></label>
                </div>
                <div class="col-md-1 form-group buttons">
                    <h4>Moeilijkheidsgraad <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.producing_difficulty') }}"></i></h4>
                    <label><input type="radio" name="moeilijkheid" value="1"  {{ (old('moeilijkheid') == 1) ? 'checked' : ($activity->difficulty_id == 1) ? 'checked' : null }} /><span>Makkelijk</span></label>
                    <label><input type="radio" name="moeilijkheid" value="2" {{ (old('moeilijkheid') == 2) ? 'checked' : ($activity->difficulty_id == 2) ? 'checked' : null }} /><span>Gemiddeld</span></label>
                    <label><input type="radio" name="moeilijkheid" value="3" {{ (old('moeilijkheid') == 3) ? 'checked' : ($activity->difficulty_id == 3) ? 'checked' : null }} /><span>Moeilijk</span></label>
                </div>
                <div class="col-md-1 form-group buttons">
                    <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;" value="Save" />
                </div>
            </div>
        {{ Form::close() }}
    </div>
@stop

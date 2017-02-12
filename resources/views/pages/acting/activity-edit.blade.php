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
        {{ Form::open(array('url' => route('process-acting-update', ['id' => $activity->laa_id]), 'class' => 'form-horizontal')) }}
            <div class="row well">
                <div class="col-md-2 form-group">
                    <h4>Activiteit</h4>
                    <input class="form-control fit-bs" type="date" name="date" value="{{ $activity->date }}" /><br/>
                    <h4>Situatie</h4>
                    <textarea class="form-control fit-bs" name="description" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/\"]{3,250}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ $activity->situation }}</textarea>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Wanneer? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_when') }}"></i></h4>
                    @foreach ($timeslots as $key => $value)
                        <label><input type="radio" name="timeslot" value="{{ $value->timeslot_id }}" {{ ($activity->timeslot_id == $value->timeslot_id) ? 'checked' : null }} /><span>{{ $value->timeslot_text }}</span></label>
                    @endforeach
                </div>
                <div class="col-md-2 from-group buttons">
                    <h4>Met wie? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_with') }}"></i></h4>
                    @foreach ($resPersons as $key => $value)
                        <label><input type="radio" name="res_person" value="{{ $value->rp_id }}" {{ ($activity->res_person_id == $value->rp_id) ? 'checked' : null }} /><span>{{ $value->person_label }}</span></label>
                    @endforeach
                </div>
                <div class="col-md-2 from-group buttons">
                    <h4>Met welke theorie? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_theory') }}"></i></h4>
                    <label><input type="radio" name="res_material" id="rm_none" value="none" {{ (!$activity->res_material_id) ? 'checked' : null }}/><span>Geen</span></label>
                    @foreach ($resMaterials as $key => $value)
                        <label><input type="radio" name="res_material" value="{{ $value->rm_id }}" {{ ($activity->res_material_id == $value->rm_id) ? 'checked' : null }} /><span>{{ $value->rm_label }}</span></label>
                    @endforeach
                    <input type="text" name="res_material_detail" id="res_material_detail" placeholder="Beschrijving bron" value="{{ $activity->res_material_detail }}" />
                </div>
                <div class="col-md-2 from-group">
                    <h4>Wat heb je geleerd?<br />Wat is het vervolg? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                    <textarea class="form-control fit-bs" name="learned" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/\"]{3,250}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ $activity->lessonslearned }}</textarea>
                    <h4>Wat heb je hierbij nodig van je werkplek? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_wp') }}"></i></h4>
                    <textarea class="form-control fit-bs" name="support_wp" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/\"]{3,125}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ $activity->support_wp }}</textarea>
                    <h4>Wat heb je hierbij nodig van de HU? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_ep') }}"></i></h4>
                    <textarea class="form-control fit-bs" name="support_ed" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/\"]{3,125}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ $activity->support_ed }}</textarea>
                </div>
                <div class="col-md-2 from-group">
                    <div>
                        <h4>Leervraag <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learninggoal') }}"></i></h4>
                        <select name="learning_goal" class="form-control fit-bs">
                            @foreach ($learningGoals as $key => $value)
                                <option value="{{ $value->learninggoal_id }}" {{ ($activity->learninggoal_id == $value->learninggoal_id) ? 'selected' : null }}>{{ $value->learninggoal_label }}</option>
                            @endforeach
                        </select>
                        <h4>Competentie <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_competence') }}"></i></h4>
                        <select name="competence" class="form-control fit-bs">
                            @foreach ($competencies as $value)
                                <option value="{{ $value->competence_id }}" {{ ($activity->getCompetencies()->competence_id == $value->competence_id) ? 'selected' : null }}>{{ $value->competence_label }}</option>
                            @endforeach
                        </select>
                        <h5>{!! str_replace('%s', LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/assets/pdf/CompetentiesLerarenopleiding.pdf", array()), Lang::get('elements.competences.competencedetails')) !!}</h5>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;" value="Save" />
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>
@stop

<?php

/** @var App\LearningActivityActing $activity */
?>

@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('activity.activities') }} - {{ Lang::get('general.edit') }}
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                // Tooltips
                (function () {
                    $('[data-toggle="tooltip"]').tooltip();
                })();

                $('.dateinput').datetimepicker({
                    locale: 'nl',
                    format: 'DD-MM-YYYY',
                    minDate: "{{ $activity->workplacelearningperiod->startdate }}",
                    maxDate: "{{ date('Y-m-d') }}",
                    useCurrent: false,
                });
            });
        </script>

        <div class="row">
            {{ Form::open(array('id' => 'taskForm', 'url' => route('process-acting-update', ['id' => $activity->laa_id]), 'class' => 'form-horizontal well', 'files' => true)) }}
            <div id="taskFormError" class="alert alert-error" style="display: none">

            </div>
            <div class="col-md-2 form-group">
                <h4>{{ Lang::get('activity.activity') }}</h4>
                <input class="form-control dateinput fit-bs" type="text" name="date"
                       value="{{ (count($errors) > 0) ? old('datum') : $activity->date->format('d-m-Y') }}"/><br/>
                <h4>{{ Lang::get('activity.situation') }}</h4>
                <textarea class="form-control fit-bs" name="description" required rows="5"
                          cols="19">{{ (count($errors) > 0) ? old('description') : $activity->situation }}</textarea>
            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.category') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="{{ trans('tooltips.acting_when') }}"></i></h4>
                @foreach ($timeslots as $key => $value)
                    <label><input type="radio" name="timeslot"
                                  value="{{ $value->timeslot_id }}" {{ (old('timeslot') == $value->timeslot_id) ? 'checked' : ($activity->timeslot_id == $value->timeslot_id) ? 'checked' : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                @endforeach
            </div>
            <div class="col-md-2 from-group buttons">
                <h4>{{ Lang::get('activity.with') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="{{ trans('tooltips.acting_with') }}"></i></h4>
                @foreach ($resourcePersons as $key => $value)
                    <label><input type="radio" name="res_person"
                                  value="{{ $value->rp_id }}" {{ (old('res_person') == $value->rp_id) ? 'checked' : ($activity->res_person_id == $value->rp_id) ? 'checked' : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                @endforeach
            </div>

            <div class="col-md-2 from-group buttons">
                <h4>{{ Lang::get('activity.theory') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                          data-toggle="tooltip" data-placement="bottom"
                                                          title="{{ trans('tooltips.acting_theory') }}"></i></h4>
                <label><input type="radio" name="res_material" id="rm_none"
                              value="none" {{ (old('res_material') === 'none' || !$activity->res_material_id) ? 'checked' : null }}/><span>{{ Lang::get('activity.none') }}</span></label>
                @foreach ($resourceMaterials as $key => $value)
                    <label><input type="radio" name="res_material"
                                  value="{{ $value->rm_id }}" {{ (old('res_material') == $value->rm_id) ? 'checked' : ($activity->res_material_id == $value->rm_id) ? 'checked' : null }} /><span>{{ $value->rm_label }}</span></label>
                @endforeach
                <input type="text" name="res_material_detail" id="res_material_detail"
                       placeholder="{{ Lang::get('activity.source-description') }}"
                       value="{{ $activity->res_material_detail }}"/>
            </div>

                <div class="col-md-2 from-group">
                    <h4>{{ Lang::get('activity.learned') }}<br/>{{ Lang::get('activity.whatnow') }} <i
                                class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="bottom"
                                title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                    <textarea class="form-control fit-bs" name="learned" required rows="5"
                              cols="19">{{ (count($errors) > 0) ? old('learned') : $activity->lessonslearned }}</textarea>
                    <h4>{{ Lang::get('activity.whatdoyouneed') }}<i class="fa fa-info-circle" aria-hidden="true"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="{{ trans('tooltips.acting_required_wp') }}"></i>
                    </h4>
                    <textarea class="form-control fit-bs" name="support_wp" rows="5"
                              cols="19">{{ (count($errors) > 0) ? old('support_wp') : $activity->support_wp }}</textarea>
                    <h4>{{ Lang::get('activity.whatdoyouneedschool') }}<i class="fa fa-info-circle" aria-hidden="true"
                                                                          data-toggle="tooltip" data-placement="bottom"
                                                                          title="{{ trans('tooltips.acting_required_ep') }}"></i>
                    </h4>
                    <textarea class="form-control fit-bs" name="support_ed" rows="5"
                              cols="19">{{ (count($errors) > 0) ? old('support_ed') : $activity->support_ed }}</textarea>
                </div>



                @if($activity->reflection)
                    @include('pages.acting.includes.edit-reflection', ['reflection' => $activity->reflection])
                @else
                    @include('pages.acting.includes.create-reflection')
                @endif


            <div class="col-md-2 from-group">
                <div>
                    <h4>{{ Lang::get('activity.learningquestion') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        title="{{ trans('tooltips.acting_learninggoal') }}"></i>
                    </h4>
                    <select name="learning_goal" class="form-control fit-bs">
                        @foreach ($learningGoals as $key => $value)
                            <option value="{{ $value->learninggoal_id }}" {{ (old('learning_goal') == $value->learninggoal_id) ? 'selected' : ($activity->learninggoal_id == $value->learninggoal_id) ? 'selected' : null }}>{{ $value->learninggoal_label }}</option>
                        @endforeach
                    </select>
                    <h4>{{ Lang::get('activity.competence') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                  data-toggle="tooltip" data-placement="bottom"
                                                                  title="{{ trans('tooltips.acting_competence') }}"></i>
                    </h4>
                    <select name="competence[]" class="form-control fit-bs" multiple>
                        @foreach ($competencies as $value)
                            <option value="{{ $value->competence_id }}" {{ in_array($value->competence_id, old('competence', $activity->competence->pluck('competence_id')->all()), false) ? 'selected' : null }}>{{ $value->localizedLabel() }}</option>
                        @endforeach
                    </select>
                    <h5>{!! str_replace('%s', "/assets/pdf/CompetentiesLerarenopleiding.pdf", Lang::get('elements.competences.competencedetails')) !!}</h5>
                </div>
                <div style="margin-top: 20px;">
                    <h4>{{ __('process.evidence') }}</h4>
                    @if($activity->evidence->count() > 0)
                        <ul>

                            @foreach($activity->evidence as $evidence)
                                <li>
                                    <?php /** App\Evidence $evidence */ ?>
                                    <a href="{{ route('evidence-download', ['learningActivity' => $evidence->id, 'diskFileName' => $evidence->disk_filename]) }}">{{ $evidence->filename }}</a>
                                    -
                                    <a href={{ route('evidence-remove', ['learningActivity' => $evidence->id]) }}>{{__('process.remove')}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <input type="file" name="evidence[]" multiple/>
                </div>
                <div>
                    <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;"
                           value="{{ __('general.save') }}"/>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>
    @include('js.activity_save')

@stop

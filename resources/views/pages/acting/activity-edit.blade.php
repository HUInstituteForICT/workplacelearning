<?php

/** @var App\LearningActivityActing $activity */
?>

@extends('layout.HUdefault')
@section('title')
    {{ __('activity.activities') }} - {{ __('general.edit') }}
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                // Tooltips
                $('[data-toggle="tooltip"]').tooltip();


                $('.dateinput').datetimepicker({
                    locale: 'nl',
                    format: 'DD-MM-YYYY',
                    minDate: "{{ $activity->workplacelearningperiod->startdate }}",
                    maxDate: "{{ date('Y-m-d') }}",
                    useCurrent: false,
                });

                // Allow multi select to be used more easily
                $('select[multiple] option').mousedown(function (e) {
                    e.preventDefault();
                    $(this).prop('selected', !this.selected);
                    return false;
                }).mousemove(function (e) {
                    e.preventDefault()
                });
            });
        </script>

        {{ Form::open(array('id' => 'taskForm', 'url' => route('process-acting-update', ['id' => $activity->laa_id]), 'files' => true)) }}
        <div id="taskFormError" class="alert alert-error" style="display: none">

        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <h3>{{ __('activity.activity') }}</h3>
                <div class="row">

                    <div class="col-md-3">
                        <h4>{{ __('activity.date') }}</h4>
                        <input class="form-control dateinput fit-bs" type="text" name="date"
                               value="{{ (count($errors) > 0) ? old('datum') : $activity->date->format('d-m-Y') }}"/><br/>

                        <h4>{{ __('activity.situation') }}</h4>
                        <div>
                        <textarea class="form-control fit-bs" name="description" required rows="8" id="description"
                                  maxlength="2000"
                                  cols="19">{{ (count($errors) > 0) ? old('description') : $activity->situation }}</textarea>
                            <a data-target-text="#description"
                               data-target-title="{{ ucfirst(trans('process_export.situation')) }}"
                               class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                        </div>
                    </div>
                    <div class="col-md-3 buttons">
                        <h4>{{ __('activity.category') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="{{ trans('tooltips.acting_when') }}"></i>
                        </h4>
                        @foreach ($timeslots as $key => $value)
                            <label><input type="radio" name="timeslot"
                                          value="{{ $value->timeslot_id }}" {{ (old('timeslot') == $value->timeslot_id) ? 'checked' : ($activity->timeslot_id == $value->timeslot_id) ? 'checked' : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-3 buttons">
                        <h4>{{ __('activity.with') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="{{ trans('tooltips.acting_with') }}"></i></h4>
                        @foreach ($resourcePersons as $key => $value)
                            <label><input type="radio" name="res_person"
                                          value="{{ $value->rp_id }}" {{ (old('res_person') == $value->rp_id) ? 'checked' : ($activity->res_person_id == $value->rp_id) ? 'checked' : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-3 buttons">
                        <h4>{{ __('activity.theory') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                  data-toggle="tooltip" data-placement="bottom"
                                                                  title="{{ trans('tooltips.acting_theory') }}"></i>
                        </h4>
                        <label><input type="radio" name="res_material" id="rm_none"
                                      value="none" {{ (old('res_material') === 'none' || !$activity->res_material_id) ? 'checked' : null }}/><span>{{ __('activity.none') }}</span></label>
                        @foreach ($resourceMaterials as $key => $value)
                            <label><input type="radio" name="res_material"
                                          value="{{ $value->rm_id }}" {{ (old('res_material') == $value->rm_id) ? 'checked' : ($activity->res_material_id == $value->rm_id) ? 'checked' : null }} /><span>{{ $value->rm_label }}</span></label>
                        @endforeach
                        <div style="text-align: center">
                            <input type="text" name="res_material_detail" id="res_material_detail" class="form-control"
                                   style="width:150px; margin: 5px;"
                                   placeholder="{{ __('activity.source-description') }}"
                                   value="{{ $activity->res_material_detail }}"/>
                        </div>

                        <div class="clearfix"></div>
                    </div>

                </div>

                <hr/>

                <div class="row">

                    <div class="col-md-3 col-md-offset-3">
                        <div>
                            <h4>{{ __('activity.learningquestion') }} <i class="fa fa-info-circle"
                                                                                aria-hidden="true"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom"
                                                                                title="{{ trans('tooltips.acting_learninggoal') }}"></i>
                            </h4>
                            <select name="learning_goal" class="form-control fit-bs">
                                @foreach ($learningGoals as $key => $value)
                                    <option value="{{ $value->learninggoal_id }}" {{ (old('learning_goal') == $value->learninggoal_id) ? 'selected' : ($activity->learninggoal_id == $value->learninggoal_id) ? 'selected' : null }}>{{ $value->learninggoal_label }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-3">

                        <div>
                            <h4>{{ __('activity.competence') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                          data-toggle="tooltip" data-placement="bottom"
                                                                          title="{{ trans('tooltips.acting_competence') }}"></i>
                            </h4>
                            <div class="wrap form-control fit-bs">
                                <div class="crop">
                                    <select id="competence-select" name="competence[]" size="{{ count($competencies) }}" multiple>
                                        @foreach ($competencies as $value)
                                            <option value="{{ $value->competence_id }}" {{ in_array($value->competence_id, old('competence', $activity->competence->pluck('competence_id')->all()), false) ? 'selected' : null }}>{{ $value->localizedLabel() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($competenceDescription !== null)
                                <h5>
                                    <a href="{{ $competenceDescription->download_url }}">{{ __('elements.competences.competencedetails') }}</a>
                                </h5>
                            @endif
                        </div>

                    </div>
                    <div class="col-md-3">

                        <div>

                            <h4>{{ __('process.evidence') }}</h4>
                            <strong>{{__('process.evidence_uploaded')}}</strong><br/>
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
                            <hr/>
                            <input type="file" name="evidence[]" multiple onchange="updateFileList(this)"/>
                            <ul id="fileList">

                            </ul>
                        </div>

                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-md-4">
                        <h3>{{__('reflection.reflection')}}</h3>
                    </div>
                    <div class="col-md-2 col-md-offset-6 text-right ">
                        @include('pages.acting.includes.reflection-settings')
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-3 shortReflection"
                         @if(!$reflectionSettings['shortReflection']) style="display: none;" @endif>
                        <h4>{{ __('activity.learned') }}, {{ __('activity.whatnow') }} <i
                                    class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                        <textarea class="form-control fit-bs" name="learned" rows="5" id="learned"
                                  cols="19">{{ (count($errors) > 0) ? old('learned') : $activity->lessonslearned }}</textarea>
                        <a data-target-text="#learned" data-target-title="{{ __('activity.learned') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>

                    <div class="col-md-3 shortReflection"
                         @if(!$reflectionSettings['shortReflection']) style="display: none;" @endif>
                        <h4>{{ __('activity.whatdoyouneed') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                         data-toggle="tooltip" data-placement="bottom"
                                                                         title="{{ trans('tooltips.acting_required_wp') }}"></i>
                        </h4>
                        <textarea class="form-control fit-bs" name="support_wp" rows="5" id="support_wp"
                                  cols="19">{{ (count($errors) > 0) ? old('support_wp') : $activity->support_wp }}</textarea>
                        <a data-target-text="#support_wp" data-target-title="{{ __('activity.whatdoyouneed') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>


                    <div class="col-md-3 shortReflection"
                         @if(!$reflectionSettings['shortReflection']) style="display: none;" @endif>
                        <h4>{{ __('activity.whatdoyouneedschool') }} <i class="fa fa-info-circle"
                                                                               aria-hidden="true"
                                                                               data-toggle="tooltip"
                                                                               data-placement="bottom"
                                                                               title="{{ trans('tooltips.acting_required_ep') }}"></i>
                        </h4>
                        <textarea class="form-control fit-bs" name="support_ed" rows="5" id="support_ed"
                                  cols="19">{{ (count($errors) > 0) ? old('support_ed') : $activity->support_ed }}</textarea>
                        <a data-target-text="#support_ed"
                           data-target-title="{{ __('activity.whatdoyouneedschool') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>


                    @if($activity->reflection)
                        @include('pages.acting.includes.edit-reflection', ['reflection' => $activity->reflection, 'reflectionSettings' => $reflectionSettings])
                    @else
                        @include('pages.acting.includes.create-reflection')
                    @endif

                </div>

                <div class="visible-xs-block visible-sm-block">
                    <hr/>


                    <input type="submit" class="btn btn-info btn-block" value="{{ __('activity.save') }}"/>

                </div>
                <div class="hidden-xs hidden-sm">
                    <div class="row">

                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-info" value="{{ __('activity.save') }}"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{ Form::close() }}
    </div>

    @include('js.activity_save')


    {{-- Modal used for enlarging fields --}}
    <div class="modal fade" id="enlargedModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea rows="10" class="form-control"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ __('general.close') }}</button>
                    <a type="button" class="btn btn-primary"
                       id="enlargedTextareaSave">{{ __('general.confirm') }}</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        var fileList = document.getElementById('fileList');

        function updateFileList(fileInput) {
            var files = [];
            for (var i = 0; i < fileInput.files.length; i++) {
                files.push(fileInput.files[i].name);
            }
            fileList.innerHTML = '';
            files.forEach(function (fileName) {
                var node = document.createElement('li');
                node.innerText = fileName;
                fileList.appendChild(node);
            });

        }

        var enlargedModal = $('#enlargedModal');
        var title = $('.modal-title');
        var textarea = $(enlargedModal).find('textarea');
        var returnTarget = undefined;
        $('.canBeEnlarged').click(function () {
            $(enlargedModal).modal('toggle');
            var returnTargetId = $(this).data('target-text');

            returnTarget = $(this).parent().find('' + returnTargetId);
            $(textarea).attr('maxlength', $(returnTarget).attr('maxlength'));
            $(textarea).val($(returnTarget).val());
            $(title).text($(this).data('target-title'));
            $(textarea).focus();
        });
        $('#enlargedTextareaSave').click(function () {
            if (returnTarget === undefined) return;

            $(returnTarget).val($(textarea).val());
            $(enlargedModal).modal('hide')
        });
    </script>

@stop

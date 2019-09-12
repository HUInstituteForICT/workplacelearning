@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                // Add new resource person or material
                $('#new-rp-hidden').hide();
                $('#new-rm-hidden').hide();
                $('#new-timeslot-hidden').hide();
                $('#res_material_detail').hide();

                $('[name="res_person"]').click(function () {
                    if ($('#new_rp').is(':checked')) {
                        $('#new-rp-hidden').fadeIn().focus();

                    } else {
                        $('#new-rp-hidden').fadeOut()();
                    }
                });

                $('[name="res_material"]').click(function () {
                    if ($('#new_rm').is(':checked')) {
                        $('#new-rm-hidden').fadeIn().focus();
                    } else {
                        $('#new-rm-hidden').fadeOut();
                    }

                    if ($('#rm_none').is(':checked')) {
                        $('#res_material_detail').fadeOut();
                    } else {
                        $('#res_material_detail').fadeIn().focus();
                    }
                });
                $('[name="timeslot"]').click(function () {
                    if ($('#new_timeslot').is(':checked')) {
                        $('#new-timeslot-hidden').fadeIn().focus();
                    } else {
                        $('#new-timeslot-hidden').fadeOut();
                    }

                });

                // Help Text
                $("#help-text").hide();

                $(".expand-click").click(function () {
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                    $("#cond-select-hidden").hide();
                    $("#rp_id").trigger("change");
                });

                $("#help-click").click(function () {
                    $('#help-text').slideToggle();
                });

                // Tooltips
                $('[data-toggle="tooltip"]').tooltip();

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
        <div class="row">
            <div class="col-md-12">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"><i
                            class="fa fa-arrow-circle-o-down"
                            aria-hidden="true"></i>&nbsp;{{__('activity.how-does-this-page-work')}}</h4>
                <div id="help-text" style="display:none">
                    <ol>
                        <li>{{ __('activity.acting.steps.1') }}</li>
                        <li>{{ __('activity.acting.steps.2') }}</li>
                        <li>{{ __('activity.acting.steps.3') }}</li>
                        <li>{{ __('activity.acting.steps.4') }}</li>
                        <li>{{ __('activity.acting.steps.5') }}</li>
                        <li>{{ __('activity.acting.steps.6') }}</li>
                        <li>{{ __('activity.acting.steps.7') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        {{ Form::open(array('id' => 'taskForm',  'url' => route('process-acting-create'), 'files' => true)) }}
        <div id="taskFormError" class="alert alert-error" style="display: none">

        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <h3>{{ __('activity.activity') }}</h3>
                <div class="row">
                    <div class="col-md-3">
                        <h4>{{ __('activity.date') }}</h4>
                        <div class='input-group date fit-bs' id='date-deadline'>
                            <input style="z-index:1;" id="datum" name="date" type='text' class="form-control"
                                   value="{{ (!is_null(old('datum'))) ? date('d-m-Y', strtotime(old('datum'))) : date('d-m-Y') }}"/>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                        <h4>{{ __('activity.situation') }}</h4>
                        <div>
                    <textarea id="description" class="form-control fit-bs" name="description" required maxlength="2000"
                              rows="8" cols="19">{{ old('description') }}</textarea>
                            <a data-target-text="#description"
                               data-target-title="{{ ucfirst(trans('process_export.situation')) }}"
                               class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                        </div>

                    </div>
                    <div class="col-md-3 buttons">
                        <h4>{{__('activity.category')}} <i class="fa fa-info-circle" aria-hidden="true"
                                                                  data-toggle="tooltip" data-placement="bottom"
                                                                  title="{{ trans('tooltips.acting_when') }}"></i></h4>
                        @foreach ($timeslots as $key => $value)
                            <label><input type="radio" name="timeslot"
                                          value="{{ $value->timeslot_id }}" {{ (old('timeslot') != null && old('timeslot') == $value->timeslot_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                        @endforeach
                        <label><input type="radio" name="timeslot" id="new_timeslot"
                                      value="new" {{ (old('timeslot') == 'new') ? 'checked' : null }}>
                            <span class="new">{{  __('activity.other') }}<br/>({{ __('activity.add') }})</span>
                            <br/>
                            <input id="new-timeslot-hidden" type="text" name="new_timeslot" class="form-control"
                                   style="width:150px;"
                                   value="{{ old('new-timeslot-hidden') }}"
                                   placeholder="{{ __('process_export.description') }}" maxlength="50"/>
                        </label>

                        <span class="clearfix"></span>
                    </div>
                    <div class="col-md-3 buttons">
                        <h4>{{ __('activity.with') }}&nbsp;<i class="fa fa-info-circle" aria-hidden="true"
                                                                     data-toggle="tooltip" data-placement="bottom"
                                                                     title="{{ trans('tooltips.acting_with') }}"></i>
                        </h4>
                        @foreach ($resourcePersons as $key => $value)
                            <label><input type="radio" name="res_person"
                                          value="{{ $value->rp_id }}" {{ (old('res_person') != null && old('res_person') == $value->rp_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                        @endforeach
                        <div>
                            <label><input type="radio" name="res_person" id="new_rp"
                                          value="new" {{ (old('res_person') == 'new') ? 'checked' : null }}><span
                                        class="new">{{ __('activity.other') }}<br/>({{ __('activity.add') }})</span>
                                <br/>
                                <input id="new-rp-hidden" type="text" name="new_rp" value="{{ old('new-rp-hidden') }}"
                                       class="form-control" style="width:150px;"
                                       placeholder="{{ __('process_export.description') }}" maxlength="50"/>
                            </label>

                        </div>
                        <span class="clearfix"></span>

                    </div>
                    <div class="col-md-3 buttons">
                        <h4>{{ __('activity.theory') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                  data-toggle="tooltip" data-placement="bottom"
                                                                  title="{{ trans('tooltips.acting_theory') }}"></i>
                        </h4>
                        <label><input type="radio" name="res_material" id="rm_none"
                                      value="none" {{ (old('res_material') === 'none' || old('res_material') === null) ? 'checked' : null }}><span>{{ __('activity.none') }}</span></label>
                        @foreach ($resourceMaterials as $key => $value)
                            <label><input type="radio" name="res_material"
                                          value="{{ $value->rm_id }}" {{ old('res_material') === $value->rm_id ? 'checked' : null }} /><span>{{ __($value->rm_label) }}</span></label>
                        @endforeach


                        <label><input type="radio" name="res_material" id="new_rm"
                                      value="new" {{ (old('res_material') == 'new') ? 'checked' : null }}><span
                                    class="new">{{ trans('activity.other') }}<br/>({{ __('activity.add') }})</span>
                            <br/>
                            <input type="text" name="new_rm" id="new-rm-hidden" value="{{ old('new_rm') }}"
                                   class="form-control" style="width:150px;"
                                   placeholder="{{ __('process_export.description') }}" maxlength="50"/>
                        </label>

                        <div style="text-align: center">
                            <input type="text" name="res_material_detail" id="res_material_detail" class="form-control"
                                   style="width:150px; margin: 5px;"
                                   placeholder="{{ __('activity.source-description') }}"
                                   value="{{ old('res_material_detail') }}"/>
                        </div>
                        <span class="clearfix"></span>

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
                                    <option value="{{ $value->learninggoal_id }}" {{ (old('learning_goal') == $value->learninggoal_id) ? 'selected' : null }}>
                                        {{ __($value->learninggoal_label) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <br/>
                    </div>
                    <div class="col-md-3">

                        <div>
                            <h4>{{ __('activity.competence') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                          data-toggle="tooltip" data-placement="bottom"
                                                                          title="{{ trans('tooltips.acting_competence') }}"></i>
                            </h4>
                            <select name="competence[]" class="form-control fit-bs" multiple>
                                @foreach ($competencies as $value)
                                    <option value="{{ $value->competence_id }}" {{ in_array($value->competence_id, old('competence', []), false) ? 'selected' : null }}>
                                        {{ $value->localizedLabel() }}
                                    </option>
                                @endforeach
                            </select>
                            @if($competenceDescription !== null)
                                <h5>
                                    <a href="{{ $competenceDescription->download_url }}">{{ __('elements.competences.competencedetails') }}</a>
                                </h5>
                            @endif
                        </div>
                        <br/>
                    </div>
                    <div class="col-md-3">

                        <div>
                            <h4>{{ __('process.evidence') }}</h4>
                            <input type="file" name="evidence[]" multiple onchange="updateFileList(this)"/>
                            <ul id="fileList">

                            </ul>
                        </div>
                        <br/>
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
                                    data-placement="bottom" title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                        <textarea id="learned" class="form-control fit-bs" name="learned" maxlength="1000"
                                  rows="5" cols="19">{{ old('learned') }}</textarea>
                        <a data-target-text="#learned" data-target-title="{{ __('activity.learned') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div class="col-md-3 shortReflection"
                         @if(!$reflectionSettings['shortReflection']) style="display: none;" @endif>
                        <h4>{{ __('activity.whatdoyouneed') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                         data-toggle="tooltip" data-placement="bottom"
                                                                         title="{{ trans('tooltips.acting_required_wp') }}"></i>
                        </h4>
                        <textarea id="support_wp" max-length="500" class="form-control fit-bs" name="support_wp"
                                  rows="5" cols="19">{{ old('support_wp') }}</textarea>
                        <a data-target-text="#support_wp" data-target-title="{{ __('activity.whatdoyouneed') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div class="col-md-3 shortReflection"
                         @if(!$reflectionSettings['shortReflection']) style="display: none;" @endif>
                        <h4>{{ __('activity.whatdoyouneedschool') }} <i class="fa fa-info-circle"
                                                                               aria-hidden="true" data-toggle="tooltip"
                                                                               data-placement="bottom"
                                                                               title="{{ trans('tooltips.acting_required_ep') }}"></i>
                        </h4>
                        <textarea id="support_ed" maxlength="500" class="form-control fit-bs" name="support_ed" rows="5"
                                  cols="19">{{ old('support_ed') }}</textarea>
                        <a data-target-text="#support_ed"
                           data-target-title="{{ __('activity.whatdoyouneedschool') }}"
                           class="canBeEnlarged">{{ trans('process.enlarge') }}</a>

                    </div>


                    @include('pages.acting.includes.create-reflection')


                </div>

                <div class="row" style="margin-top:25px;">
                    <div class="col-md-12 text-right">
                        <input type="submit" class="btn btn-lg btn-info" value="{{ __('activity.save') }}"/>
                    </div>
                </div>


            </div>
        </div>
        {{ Form::close() }}
        @include('js.activity_save')


        <script type="text/javascript">
            $(document).ready(function () {
                $('#date-deadline').datetimepicker({
                    locale: 'nl',
                    format: 'DD-MM-YYYY',
                    minDate: "{{ $workplacelearningperiod->startdate }}",
                    maxDate: "{{ date('Y-m-d', strtotime("now")) }}",
                    useCurrent: false,
                });
            }).on('dp.change', function (e) {
                $('#datum').attr('value', moment(e.date).format("DD-MM-YYYY"));
            });
        </script>


        <hr>


        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
                window.reflectionDownloadMultipleUrl = '{{ route('reflection-download-multiple') }}';
                window.exportActivitiesUrl = '{{ route('acting-activities-word-export') }}';
                window.mailExportActivitiesUrl = '{{ route('mail-acting-activities-word-export') }}';
                window.activityActingTableMode = 'mini';
                window.progressLink = '{{ route('progress-acting') }}';
            </script>

            <div id="ActivityActingProcessTable" class="__reactRoot col-md-12"></div>
        </div>

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
    </div>
@stop

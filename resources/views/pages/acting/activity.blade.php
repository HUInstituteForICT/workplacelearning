@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function() {
                // Add new resource person or material
                (function() {
                    $('#new-rp-hidden').hide();
                    $('#new-rm-hidden').hide();
                    $('#new-timeslot-hidden').hide();
                    $('#res_material_detail').hide();

                    $('[name="res_person"]').click(function() {
                        if ($('#new_rp').is(':checked')) {
                            $('#new-rp-hidden').show();
                        } else {
                            $('#new-rp-hidden').hide();
                        }
                    });

                    $('[name="res_material"]').click(function() {
                        if ($('#new_rm').is(':checked')) {
                            $('#new-rm-hidden').show();
                        } else {
                            $('#new-rm-hidden').hide();
                        }

                        if ($('#rm_none').is(':checked')) {
                            $('#res_material_detail').hide();
                        } else {
                            $('#res_material_detail').show();
                        }
                    });
                    $('[name="timeslot"]').click(function() {
                        if ($('#new_timeslot').is(':checked')) {
                            $('#new-timeslot-hidden').show();
                        } else {
                            $('#new-timeslot-hidden').hide();
                        }

                    });
                })();

                // Help Text
                (function() {
                    $("#help-text").hide();

                    $(".expand-click").click(function(){
                        $(".cond-hidden").hide();
                        $(this).siblings().show();
                        $("#cond-select-hidden").hide();
                        $("#rp_id").trigger("change");
                    });

                    $("#help-click").click(function(){
                        $('#help-text').slideToggle('slow');
                    });
                })();

                // Tooltips
                (function() {
                    $('[data-toggle="tooltip"]').tooltip();
                })();
            });
        </script>
        <div class="row">
            <div class="col-md-12 well">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>&nbsp;{{Lang::get('activity.how-does-this-page-work')}}</h4>
                <div id="help-text" style="display:none">
                    <ol>
                        <li>{{ Lang::get('activity.acting.steps.1') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.2') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.3') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.4') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.5') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.6') }}</li>
                        <li>{{ Lang::get('activity.acting.steps.7') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            {{ Form::open(array('id' => 'taskForm',  'url' => route('process-acting-create'), 'class' => 'form-horizontal well', "files" => true)) }}
                <div id="taskFormError" class="alert alert-error" style="display: none">

                </div>
                <div class="col-md-2 form-group">
                    <h4>{{ Lang::get('activity.activity') }}</h4>
                    <div class='input-group date fit-bs' id='date-deadline'>
                        <input style="z-index:1;" id="datum" name="date" type='text' class="form-control" value="{{ (!is_null(old('datum'))) ? date('d-m-Y', strtotime(old('datum'))) : date('d-m-Y') }}"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <h4>{{ Lang::get('activity.situation') }}</h4>
                    <div>
                        <textarea id="description" class="form-control fit-bs" name="description" required  maxlength="2000" rows="16" cols="19">{{ old('description') }}</textarea>
                        <a data-target-text="#description" data-target-title="{{ ucfirst(trans('process_export.situation')) }}" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>

                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>{{Lang::get('activity.category')}} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_when') }}"></i></h4>
                    @foreach ($timeslots as $key => $value)
                        <label><input type="radio" name="timeslot" value="{{ $value->timeslot_id }}" {{ (old('timeslot') != null && old('timeslot') == $value->timeslot_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                    @endforeach
                    <div>
                        <label><input type="radio" name="timeslot" id="new_timeslot" value="new" {{ (old('timeslot') == 'new') ? 'checked' : null }}><span class="new">{{  Lang::get('activity.other') }}<br />({{ Lang::get('activity.add') }})</span></label>
                        <input id="new-timeslot-hidden" type="text" name="new_timeslot" value="{{ old('new-timeslot-hidden') }}" placeholder="{{ Lang::get('process_export.description') }}"  maxlength="50"/>
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>{{ Lang::get('activity.with') }}<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_with') }}"></i></h4>
                    @foreach ($resourcePersons as $key => $value)
                        <label><input type="radio" name="res_person" value="{{ $value->rp_id }}" {{ (old('res_person') != null && old('res_person') == $value->rp_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->localizedLabel() }}</span></label>
                    @endforeach
                    <div>
                        <label><input type="radio" name="res_person" id="new_rp" value="new" {{ (old('res_person') == 'new') ? 'checked' : null }}><span class="new">{{ Lang::get('activity.other') }}<br />({{ Lang::get('activity.add') }})</span></label>
                        <input id="new-rp-hidden" type="text" name="new_rp" value="{{ old('new-rp-hidden') }}" placeholder="{{ Lang::get('process_export.description') }}" maxlength="50" />
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>{{ Lang::get('activity.theory') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_theory') }}"></i></h4>
                    <label><input type="radio" name="res_material" id="rm_none" value="none" {{ (old('res_material') === 'none' || old('res_material') === null) ? 'checked' : null }}><span>{{ Lang::get('activity.none') }}</span></label>
                    @foreach ($resourceMaterials as $key => $value)
                        <label><input type="radio" name="res_material" value="{{ $value->rm_id }}" {{ (old('res_material') != null && old('res_material') == $value->rm_id) ? "checked" : null }} /><span>{{ __($value->rm_label) }}</span></label>
                    @endforeach
                    <input type="text" name="res_material_detail" id="res_material_detail" placeholder="{{ Lang::get('activity.source-description') }}" value="{{ old('res_material_detail') }}" />
                    <label><input type="radio" name="res_material" id="new_rm" value="new" {{ (old('res_material') == 'new') ? 'checked' : null }}><span class="new">{{ trans('activity.other') }}<br />({{ Lang::get('activity.add') }})</span></label>
                    <input type="text" name="new_rm" id="new-rm-hidden" value="{{ old('new_rm') }}" placeholder="{{ Lang::get('process_export.description') }}" maxlength="50"/>
                </div>

            @if(!$reflectionBetaActive)
                <div class="col-md-2 form-group">
                    <div>
                        <h4>{{ Lang::get('activity.learned') }}<br />{{ Lang::get('activity.whatnow') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                        <textarea id="learned" class="form-control fit-bs" name="learned" required maxlength="1000" rows="5" cols="19">{{ old('learned') }}</textarea>
                        <a data-target-text="#learned" data-target-title="{{ Lang::get('activity.learned') }}" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div>
                        <h4>{{ Lang::get('activity.whatdoyouneed') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_wp') }}"></i></h4>
                        <textarea id="support_wp" max-length="500" class="form-control fit-bs" name="support_wp" rows="5" cols="19">{{ old('support_wp') }}</textarea>
                        <a data-target-text="#support_wp" data-target-title="{{ Lang::get('activity.whatdoyouneed') }}" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div>
                        <h4>{{ Lang::get('activity.whatdoyouneedschool') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_ep') }}"></i></h4>
                        <textarea id="support_ed" maxlength="500" class="form-control fit-bs" name="support_ed" rows="5" cols="19">{{ old('support_ed') }}</textarea>
                        <a data-target-text="#support_ed" data-target-title="{{ Lang::get('activity.whatdoyouneedschool') }}" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>

                    </div>
                </div>
            @endif
                <div class="col-md-2 form-group">
                    <div>
                        <h4>{{ Lang::get('activity.learningquestion') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learninggoal') }}"></i></h4>
                        <select name="learning_goal" class="form-control fit-bs">
                            @foreach ($learningGoals as $key => $value)
                                <option value="{{ $value->learninggoal_id }}" {{ (old('learning_goal') == $value->learninggoal_id) ? 'selected' : null }}>{{ __($value->learninggoal_label) }}</option>
                            @endforeach
                        </select>
                        <h4>{{ Lang::get('activity.competence') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_competence') }}"></i></h4>
                        <select name="competence[]" class="form-control fit-bs" multiple>
                            @foreach ($competencies as $value)
                                <option value="{{ $value->competence_id }}" {{ in_array($value->competence_id, old('competence', []), false) ? 'selected' : null }}>{{ $value->localizedLabel() }}</option>
                            @endforeach
                        </select>
                        @if($competenceDescription !== null)
                            <h5>
                                <a href="{{ $competenceDescription->download_url }}">{{ Lang::get('elements.competences.competencedetails') }}</a>
                            </h5>
                        @endif
                    </div>
                    <div style="margin-top: 20px;">
                        <h4>{{ __('process.evidence') }}</h4>
                        <input type="file" name="evidence[]" multiple onchange="updateFileList(this)"/>
                        <ul id="fileList">

                        </ul>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;" value="Save" />
                    </div>
                </div>

            @if($reflectionBetaActive)
                @include('pages.acting.includes.create-reflection')
            @endif
            {{ Form::close() }}
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#date-deadline').datetimepicker({
                        locale: 'nl',
                        format: 'DD-MM-YYYY',
                        minDate: "{{ $workplacelearningperiod->startdate }}",
                        maxDate: "{{ date('Y-m-d', strtotime("now")) }}",
                        useCurrent: false,
                    });
                }).on('dp.change', function(e) {
                    $('#datum').attr('value', moment(e.date).format("DD-MM-YYYY"));
                });
            </script>
            @include('js.activity_save')


        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
            </script>

            <div id="ActivityActingProcessTable" class="__reactRoot col-md-12"></div>
        </div>

        {{-- Modal used for enlarging fields --}}
        <div class="modal fade" id="enlargedModal" tabindex="-1" role="dialog">
            <div class="modal-dialog"  role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('general.close') }}</button>
                        <a type="button" class="btn btn-primary" id="enlargedTextareaSave">{{ Lang::get('general.confirm') }}</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script>
            var fileList = document.getElementById('fileList');

            function updateFileList(fileInput) {
                console.log(fileInput);
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
            $('.canBeEnlarged').click(function() {
                $(enlargedModal).modal('toggle');
                var returnTargetId = $(this).data('target-text');

                returnTarget = $(this).parent().find('' + returnTargetId);
                $(textarea).attr('maxlength', $(returnTarget).attr('maxlength'));
                $(textarea).val($(returnTarget).val());
                $(title).text($(this).data('target-title'));
                $(textarea).focus();
            });
            $('#enlargedTextareaSave').click(function() {
                if(returnTarget === undefined) return;

                $(returnTarget).val($(textarea).val());
                $(enlargedModal).modal('hide')
            });
        </script>
    </div>
@stop

@extends('layout.HUdefault')

<?php
/** @var \App\LearningActivityProducing $activity */use App\ResourcePerson;

/** @var \App\ResourcePerson $resourcePersons */
/** @var bool $isCustomActivityDuration */
$isCustomActivityDuration = !in_array($activity->duration, [0.25, 0.50, 0.75, 1.0, 2.0, 3.0, 4.0, 5.0, 6.0], false);

?>

@section('title')
    {{ __('activity.activities') }} - {{ __('general.edit') }}
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                // Tooltips

                // $('#custom_hours_container').hide();
                $("#hours_custom").click(function () {
                    $('#custom_hours_container').show();
                });

                $('.predefinedHours').click(function () {
                    $('#custom_hours_container').hide();
                });

                (function () {
                    $('[data-toggle="tooltip"]').tooltip();
                })();

                // Resource person
                (function () {
                    $(".cond-hidden").hide();
                    $("#cond-select-hidden").hide();
                    $("#category").hide();

                    $(".expand-click").click(function () {
                        $(".cond-hidden").hide();
                        $(this).siblings().show();
                        $("#cond-select-hidden").hide();
                        $("#rp_id").trigger("change");
                    });

                    $('[name="resource"]:checked').each(function () {
                        $('[name="personsource"]').hide();
                        $('[name="internetsource"]').hide();
                        $('[name="booksource"]').hide();
                        switch (this.value) {
                            case 'persoon':
                                $('[name="personsource"]').show();
                                break;
                            case 'internet':
                                $('[name="internetsource"]').show();
                                break;
                            case 'boek':
                                $('[name="booksource"]').show();
                        }
                    });
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

        @card

        <h2>{{ __('activity.activity') }}</h2>
        <div id="taskFormError" class="alert alert-error" style="display: none">

        </div>

        {!! Form::open(array('id' => 'taskForm', 'url' => route('process-producing-update', [$activity->lap_id]), 'class' => 'form-horizontal')) !!}
        <div class="row">

            <div class="col-md-3">
                <h4>{{ __('activity.date') }}</h4>
                <input class="form-control dateinput fit-bs" type="text" name="datum"
                       value="{{ (count($errors) > 0) ? old('datum') : $activity->date->format('d-m-Y') }}"/><br/>

                <h5>{{ __('activity.description') }}:</h5>
                <textarea class="form-control fit-bs" name="omschrijving" required maxlength="300" rows="5"
                          cols="19">{{ (count($errors) > 0) ? old('omschrijving') : $activity->description }}</textarea>

                <h5>{{ __('activity.chain-to') }}:</h5>
                <select class="form-control fit-bs" id="chainSelect" name="chain_id">
                    <option value="-1">{{ __('process.chain.none') }}</option>
                    @foreach($chains as $chain)
                        <option value="{{ $chain->id }}"
                                @if($activity->chain_id === $chain->id) selected @endif
                                @if($chain->status === \App\Chain::STATUS_FINISHED) disabled @endif
                        >{{ $chain->name }} @if($chain->status === \App\Chain::STATUS_FINISHED)
                                ({{ strtolower(__('process.chain.finished')) }}) @endif</option>
                    @endforeach

                </select>
                @include('pages.producing.chain-partial')

            </div>


            <div class="col-md-3">
                <div class="buttons numpad">
                    <h4>{{ __('activity.hours') }}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label><input type="radio" name="aantaluren" value="0.25"
                                        {{ (float) old('aantaluren', $activity->duration) === 0.25 ? 'checked' : null }}
                                ><span>15 <em>min.</em></span></label>
                        </div>
                        <div class="col-md-4">
                            <label><input type="radio" name="aantaluren"
                                          {{ (float) old('aantaluren', $activity->duration) === 0.50 ? 'checked' : null }}
                                          value="0.50"><span>30 <em>min.</em></span></label>
                        </div>
                        <div class="col-md-4">
                            <label><input type="radio" name="aantaluren"
                                          {{ (float) old('aantaluren', $activity->duration) === 0.75 ? 'checked' : null }}
                                          value="0.75"><span>45 <em>min.</em></span></label>

                            @for($i = 1; $i <= 6; $i++)
                        </div>
                        <div class="col-md-4">
                            <label>
                                <input type="radio" name="aantaluren" value="{{$i}}"
                                        {{ old('aantaluren', $activity->duration) === (float) $i ? 'checked'  : null }}
                                />
                                <span>{{ $i }} <em>{{ trans_choice('elements.tasks.hour', $i) }}</em></span>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <hr/>
                    <div class="custom">
                        <label id="hours_custom"><input type="radio" name="aantaluren"
                                                        @if($isCustomActivityDuration) checked @endif
                                                        value="x"/><span>{{ __('activity.other') }}</span></label>
                        <br/>
                        <div id="custom_hours_container" class=""
                             style="margin-left: 5px; width:100%; @if(!$isCustomActivityDuration) display:none; @endif">

                            <div class="input-group">

                                @if($isCustomActivityDuration)
                                    <input class="form-control" type="number" step="1" min="1" max="480"
                                           name="aantaluren_custom" value="{{ round($activity->duration*60) }}">
                                    <script>
                                        (function () {
                                            setTimeout(function () {
                                                $('#custom_hours_container').show();
                                            }, 500)
                                        })()
                                    </script>
                                @else
                                    <input class="form-control" type="number" step="1" min="1" max="480"
                                           name="aantaluren_custom" value="60">

                                @endif
                                <span class="input-group-addon">{{ __('dashboard.minutes') }}</span>
                            </div>

                            <div class="btn-group btn-group-justified" style="width:100%; margin-top: 5px;">
                                <a class="btn btn-danger" id="hourDecrease">-</a>
                                <a class="btn btn-success" id="hourIncrease">+</a>
                            </div>


                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="buttons">

                    <h4>{{ __('activity.category') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                         data-toggle="tooltip" data-placement="bottom"
                                                         title="{{ trans('tooltips.producing_category') }}"></i></h4>
                    @foreach($categories as $key => $value)
                        <label><input type="radio" name="category_id"
                                      value="{{ $value->category_id }}" {{ ((old('category_id') == $value->category_id) ? 'checked' : ($activity->category_id == $value->category_id)) ? 'checked' : null }}/><span>{{ $value->localizedLabel() }}</span></label>
                    @endforeach
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="buttons">
                    <h4>{{ __('activity.work-learn-with') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="{{ trans('tooltips.producing_with') }}"></i>
                    </h4>
                    <div id="swvcontainer">
                        <label class="expand-click"><input type="radio" name="resource"
                                                           value="persoon" {{ old('resource') === 'persoon' || $activity->res_person_id ? 'checked' : null  }} /><span>{{ __('activity.person') }}</span></label>
                        <select class="form-control" id="rp_id" name="personsource" class="cond-hidden"
                                style="width: 150px; margin: 5px;">
                            <?php /** @var ResourcePerson $resourcePerson */ ?>
                            @foreach($resourcePersons as $resourcePerson)
                                <option value="{{ $resourcePerson->rp_id }}"
                                        {{ (int) old('personsource', $activity->res_person_id) === $resourcePerson->rp_id ? 'selected' : null }}>
                                    {{ $resourcePerson->localizedLabel() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="solocontainer">
                        <label class="expand-click"><input type="radio" name="resource"
                                                           value="alleen" {{ old('resource') === 'alleen' || ($activity->res_person_id === null && $activity->res_material_id === null) ? 'checked' : null }} /><span>{{ __('activity.alone') }}</span></label>
                    </div>
                    <div id="internetcontainer">
                        <label class="expand-click"><input type="radio" name="resource"
                                                           value="internet" {{ ((old('resource') === 'internet') ? 'checked' : (!old('resource') && $activity->res_material_id == 1)) ? 'checked' : null }} /><span>{{ __('activity.internetsource') }}</span></label>
                        <input class="cond-hidden form-control" type="text" name="internetsource"
                               value="{{ old('internetsource', $activity->res_material_detail) }}"
                               style="width: 150px; margin: 5px;"
                               placeholder="http://www.bron.domein/"/>
                    </div>
                    <div id="boekcontainer">
                        <label class="expand-click"><input type="radio" name="resource"
                                                           value="boek" {{ ((old('resource') == 'boek') ? 'checked' : (!old('resource') && $activity->res_material_id == 2)) ? 'checked' : null }} /><span>{{ __('activity.book') }}
                            /{{ __('activity.article') }}</span></label>
                        <input class="cond-hidden form-control" type="text" name="booksource" maxlength="150"
                               style="width: 150px; margin: 5px;"
                               value="{{ old('booksource', $activity->res_material_detail)  }}"
                               placeholder="{{ __('dashboard.name') }}{{ __('activity.book') }}/{{ __('activity.article') }}"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-3 col-md-offset-3">
                <div class="buttons">
                    <h4>{{ __('activity.status') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                       data-toggle="tooltip" data-placement="bottom"
                                                       title="{{ trans('tooltips.producing_status') }}"></i></h4>
                    <label><input type="radio" name="status"
                                  value="1" {{ ((int) old('status', $activity->status_id) === 1) ? 'checked' :  null }} /><span>{{ __('activity.finished') }}</span></label>
                    <label><input type="radio" name="status"
                                  value="2" {{ ((int) old('status', $activity->status_id) === 2) ? 'checked' :  null }} /><span>{{ __('activity.busy') }}</span></label>
                    <label><input type="radio" name="status"
                                  value="3" {{ ((int) old('status', $activity->status_id) === 3) ? 'checked' :  null }} /><span>{{ __('activity.transferred') }}</span></label>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="buttons">
                    <h4>{{ __('activity.difficulty') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                          data-toggle="tooltip" data-placement="bottom"
                                                          title="{{ trans('tooltips.producing_difficulty') }}"></i>
                    </h4>
                    <label><input type="radio" name="moeilijkheid"
                                  value="1" {{ ((int) old('moeilijkheid', $activity->difficulty_id) === 1) ? 'checked' : null }} /><span>{{ __('activity.easy') }}</span></label>
                    <label><input type="radio" name="moeilijkheid"
                                  value="2" {{ ((int) old('moeilijkheid', $activity->difficulty_id) === 2) ? 'checked' : null }}/><span>{{ __('activity.average') }}</span></label>
                    <label><input type="radio" name="moeilijkheid"
                                  value="3" {{ ((int) old('moeilijkheid', $activity->difficulty_id) === 3) ? 'checked' : null }} /><span>{{ __('activity.hard') }}</span></label>
                    <div class="clearfix"></div>
                </div>
            </div>

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

        @endcard

        {{ Form::close() }}

    </div>

    <script>
        (function () {

            $('input[name="aantaluren"]').click(function () {
                if ($(this).attr('id') !== 'hours_custom') {
                    $('input[name="aantaluren_custom"]').val('60');
                    $('#custom_hours_container').hide();
                }
            });

            $("#hours_custom").click(function () {
                $('#custom_hours_container').show();
            });

            $('#hourDecrease').click(function () {
                const newVal = Math.max(0, parseInt($('input[name="aantaluren_custom"]').val()) - 15);
                $('input[name="aantaluren_custom"]').val(newVal);
            });

            $('#hourIncrease').click(function () {
                const newVal = parseInt($('input[name="aantaluren_custom"]').val()) + 15;
                $('input[name="aantaluren_custom"]').val(newVal);
            });

            $(".expand-click").click(resourcePersonUIUpdate);

            function resourcePersonUIUpdate() {
                $(".cond-hidden").hide();
                $(this).siblings().show();
                $("#cond-select-hidden").hide();
                $("#rp_id").trigger("change");
                if($('input[name="resource"]:checked').val() === 'persoon') {
                    $('#rp_id').show();
                } else {
                    $('#rp_id').hide();
                }
            }
        })()
    </script>
    @include('js.activity_save')

@stop

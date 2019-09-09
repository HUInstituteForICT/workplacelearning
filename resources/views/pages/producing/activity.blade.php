<?php
/** @var ResourcePerson[] $resourcePersons */

use App\Category;
use App\Chain;
use App\ResourcePerson;

/** @var Category[] $categories */
/** @var Chain[] $chains */
?>
@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                $('#custom_hours_container').hide();
                $("#rp_id").on('change', function () {
                    if ($(this).val() == "new" && $(this).is(":visible")) {
                        $("#cond-select-hidden").show();
                    } else {
                        $("#cond-select-hidden").hide();
                    }
                });
                $(".expand-click").click(function () {
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                    $("#cond-select-hidden").hide();
                    $("#rp_id").trigger("change");
                });
                $("#hours_custom").click(function () {
                    $('#custom_hours_container').show();
                });
                $("#help-click").click(function () {
                    $('#help-text').slideToggle('slow');
                });
                $(".cond-hidden").hide();
                $("#cond-select-hidden").hide();
                $("#category").hide();
                $("#help-text").hide();
                $(".expand-click :input[value='persoon']").click();
                $("#newcat").click(function () {
                    $("#category").show();
                });

                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"><i
                            class="fa fa-arrow-circle-o-down"
                            aria-hidden="true"></i> {{ __('activity.how-does-this-page-work') }}</h4>
                <div id="help-text" style="display: none">
                    <ol>
                        <li>{{ __('activity.producing.steps.1') }}</li>
                        <li>{{ __('activity.producing.steps.2') }}</li>
                        <li>{{ __('activity.producing.steps.3') }}</li>
                        <li>{{ __('activity.producing.steps.4') }}</li>
                        <li>{{ __('activity.producing.steps.5') }}</li>
                        <li>{{ __('activity.producing.steps.6') }}</li>
                        <li>{{ __('activity.producing.steps.7') }}</li>
                        <li>{{ __('activity.producing.steps.8') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="panel panel-default">

            <div class="panel-body">
                <h2>{{ __('activity.activity') }}</h2>

                <div id="taskFormError" class="alert alert-error" style="display: none">

                </div>
                {!! Form::open(array('id' => 'taskForm', 'url' => route('process-producing-create'))) !!}
                <div class="row">


                    <div class="col-md-3 form-group">
                        <h4>{{ __('activity.date') }}</h4>

                        <div class='input-group date fit-bs' id='date-deadline'>
                            <input id="datum" name="datum" type='text' class="form-control"
                                   value="{{ (!is_null(old('datum'))) ? date('d-m-Y', strtotime(old('datum'))) : date('d-m-Y') }}"/>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>

                        <h5>{{ __('activity.description') }}:</h5>
                        <textarea class="form-control fit-bs" name="omschrijving" required maxlength="300" rows="5"
                                  cols="19">{{ old('omschrijving') }}</textarea>


                        <h5>{{ __('activity.chain-to') }}:</h5>

                        <select class="form-control fit-bs" id="chainSelect" name="chain_id">
                            <option value="-1">{{ __('process.chain.none') }}</option>
                            @foreach($chains as $chain)
                                @if($chain->status === \App\Chain::STATUS_BUSY)
                                    <option id="chain-select-{{ $chain->id }}" value="{{ $chain->id }}">
                                        {{ $chain->name }}
                                        &nbsp;{{ '(' . $chain->hours()  . ' ' . strtolower(__('activity.hours')) . ')' }}
                                    </option>
                                @endif
                            @endforeach

                        </select>

                        @include('pages.producing.chain-partial')
                    </div>
                    <div class="col-md-3 buttons numpad form-group">
                        <h4>{{ __('activity.hours') }}</h4>
                        <label><input type="radio" name="aantaluren" value="0.25" checked><span>15 min.</span></label>
                        <label><input type="radio" name="aantaluren" value="0.50"><span>30 min.</span></label>
                        <label><input type="radio" name="aantaluren" value="0.75"><span>45 min.</span></label>
                        @for($i = 1; $i <= 6; $i++)
                            <label>
                                <input type="radio" name="aantaluren" value="{{$i}}"/>
                                <span>{{ $i }} {{ trans_choice('elements.tasks.hour', $i) }}</span>
                            </label>
                        @endfor
                        <div class="custom">
                            <label id="hours_custom"><input type="radio" name="aantaluren"
                                                            value="x"/><span>{{ __('activity.other') }}</span></label>
                            <br/>
                            <div id="custom_hours_container">
                                <input class="form-control" type="number" step="1" min="1" max="480"
                                       name="aantaluren_custom" value="5">
                                &nbsp;
                                {{ __('dashboard.minutes') }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-3 buttons form-group">
                        <h4>{{ __('activity.category') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                             data-toggle="tooltip" data-placement="bottom"
                                                             title="{{ trans('tooltips.producing_category') }}"></i>
                        </h4>
                        <div>
                            @foreach($categories as $category)
                                <label>
                                    <input type="radio" name="category_id"
                                           value="{{ $category->category_id }}"
                                            {{ ($loop->first) ? 'checked' : null }}/>
                                    <span>{{ $category->localizedLabel() }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div>
                            <label class="newcat"><input type="radio" name="category_id" value="new"/><span class="new"
                                                                                                            id="newcat">{{ __('activity.other') }}<br/>({{ __('activity.add') }})</span></label>
                            <input id="category" type="text" maxlength="50" name="newcat"
                                   placeholder="{{ __('activity.description') }}"/>
                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="col-md-2 buttons form-group">
                        <h4>{{ __('activity.work-learn-with') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="{{ trans('tooltips.producing_with') }}"></i>
                        </h4>
                        <div id="swvcontainer">

                            <label class="expand-click">
                                <input type="radio" name="resource" value="persoon" checked/>
                                <span>{{ __('activity.person') }}</span>
                            </label>

                            <select id="rp_id" name="personsource" class="cond-hidden select form-control"
                                    style="width: 150px;">
                                @foreach($resourcePersons as $resourcePerson)
                                    <option value="{{ $resourcePerson->rp_id }}">{{ __($resourcePerson->localizedLabel()) }}</option>
                                @endforeach
                                <option value="new">{{ __('general.new') }}
                                    / {{ __('activity.other') }}</option>
                            </select>

                            <input id="cond-select-hidden" type="text" maxlength="50" name="newswv"
                                   placeholder="Omschrijving"/>


                        </div>
                        <div id="solocontainer">
                            <label class="expand-click"><input type="radio" name="resource"
                                                               value="alleen"/><span>{{ __('activity.alone') }}</span></label>
                        </div>
                        <div id="internetcontainer">
                            <label class="expand-click"><input type="radio" name="resource"
                                                               value="internet"/><span>{{ __('activity.internetsource') }}</span></label>
                            <input class="cond-hidden" type="text" name="internetsource"
                                   value="{{ old('internetsource') }}" placeholder="http://www.source.com/"/>
                        </div>
                        <div id="boekcontainer">
                            <label class="expand-click"><input type="radio" name="resource" value="boek"/><span>{{ __('activity.book') }}/{{ __('activity.article') }}</span></label>
                            <input class="cond-hidden" type="text" name="booksource" maxlength="150"
                                   value="{{ old('booksource')  }}"
                                   placeholder="{{ __('dashboard.name') }} {{ __('activity.book') }} / {{ __('activity.article') }}"/>
                        </div>
                    </div>

                </div>

                <hr/>

                <div class="row">

                    <div class="col-md-3 form-group buttons col-md-offset-3">
                        <h4>{{ __('activity.status') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                           data-toggle="tooltip" data-placement="bottom"
                                                           title="{{ trans('tooltips.producing_status') }}"></i>
                        </h4>
                        <label><input type="radio" name="status" value="1"
                                      checked/><span>{{ __('activity.finished') }}</span></label>
                        <label><input type="radio" name="status"
                                      value="2"/><span>{{ __('activity.busy') }}</span></label>
                        <label><input type="radio" name="status"
                                      value="3"/><span>{{ __('activity.transfered') }}</span></label>
                    </div>
                    <div class="col-md-3 form-group buttons">
                        <h4>{{ __('activity.difficulty') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                               data-toggle="tooltip" data-placement="bottom"
                                                               title="{{ trans('tooltips.producing_difficulty') }}"></i>
                        </h4>
                        <label><input type="radio" name="moeilijkheid" value="1"
                                      checked/><span>{{ __('activity.easy') }}</span></label>
                        <label><input type="radio" name="moeilijkheid"
                                      value="2"/><span>{{ __('activity.average') }}</span></label>
                        <label><input type="radio" name="moeilijkheid"
                                      value="3"/><span>{{ __('activity.hard') }}</span></label>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4 col-lg-2 col-md-offset-8 col-lg-offset-10">
                        <div class="visible-xs-block visible-sm-block">
                            <hr/>
                        </div>
                        <input type="submit" class="btn btn-info btn-block" value="{{ __('activity.save') }}" />

                    </div>

                </div>

                {{ Form::close() }}
            </div>

        </div>

    </div>


    <div class="row">
        <script>
            window.activities = {!! $activitiesJson !!};
            window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
        </script>

        <div id="ActivityProducingProcessTable" class="__reactRoot col-md-12"></div>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name="aantaluren"]').click(function () {
                if ($(this).attr('id') !== 'hours_custom') {
                    $('input[name="aantaluren_custom"]').val('5');
                    $('#custom_hours_container').hide();
                }
            });

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
    @include('js.activity_save')
@stop

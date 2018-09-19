@extends('layout.HUdefault')

<?php
/** @var \App\LearningActivityProducing $activity */
/** @var bool $isCustomActivityDuration */
$isCustomActivityDuration = !in_array($activity->duration, [0.25, 0.50, 0.75, 1.0, 2.0, 3.0, 4.0, 5.0, 6.0], false);

?>

@section('title')
    {{ Lang::get('activity.activities') }} - {{ Lang::get('general.edit') }}
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

                $('.predefinedHours').click(function() {
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
            });
        </script>
        {{ Form::open(array('url' => route('process-producing-update', ['id' => $activity->lap_id]), 'class' => 'form-horizontal')) }}
        <div class="row well">
            <div class="col-md-2 form-group">
                <h4>{{ Lang::get('activity.activity') }}</h4>
                <input class="form-control fit-bs" type="date" name="datum"
                       value="{{ (count($errors) > 0) ? old('datum') : $activity->date }}"/><br/>
                <h5>{{ Lang::get('activity.description') }}:</h5>
                <textarea class="form-control fit-bs" name="omschrijving" required maxlength="250" rows="5"
                          cols="19">{{ (count($errors) > 0) ? old('omschrijving') : $activity->description }}</textarea>

                <h5>{{ Lang::get('activity.chain-to') }}:</h5>
                <select class="form-control fit-bs" id="chainSelect" name="chain_id">
                    <option value="-1">{{ Lang::get('process.chain.none') }}</option>
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
            <div class="col-md-2 form-group buttons numpad">
                <h4>{{ Lang::get('activity.hours') }}</h4>

                <label class="predefinedHours"><input type="radio" name="aantaluren" value="0.25"
                            {{ (float) old('aantaluren', $activity->duration) === 0.25 ? 'checked' : null }}>
                    <span>15 min.</span>
                </label>
                <label class="predefinedHours"><input type="radio" name="aantaluren" value="0.50"
                            {{ (float) old('aantaluren', $activity->duration) === 0.50 ? 'checked' : null }}>
                    <span>30 min.</span>
                </label>
                <label class="predefinedHours"><input type="radio" name="aantaluren" value="0.75"
                            {{ (float) old('aantaluren', $activity->duration) === 0.75 ? 'checked'  : null }}>
                    <span>45 min.</span>
                </label>
                {{-- NEEDS TO BE FLOATS because hours are saved as floats in db... --}}
                @for($i = 1.0; $i <= 6.0; $i++)
                    <label class="predefinedHours"><input type="radio" name="aantaluren" value="{{ $i }}"
                                {{ (float) old('aantaluren', $activity->duration) === $i ? 'checked'  : null }}>
                        <span>{{ $i . ' ' . Lang::choice('elements.tasks.hour', $i) }}</span>
                    </label>
                @endfor

                <div class="custom">
                    <label id="hours_custom">
                        <input type="radio" name="aantaluren" value="x"
                               @if($isCustomActivityDuration) checked @endif/>
                        <span>{{ Lang::get('activity.other') }}</span>
                    </label>

                    <br/>
                    <div id="custom_hours_container"
                         @if(!$isCustomActivityDuration)
                         style="display:none"
                            @endif>
                        {{-- If duration is not in the array we can assume user submitted custom minutes, thus render that --}}
                        @if($isCustomActivityDuration)
                            <input class="form-control" type="number" step="1" min="1" max="480"
                                   name="aantaluren_custom" value="{{ round($activity->duration*60) }}">
                            &nbsp;{{ Lang::get('dashboard.minutes') }}
                            <script>
                                (function () {
                                    setTimeout(function () {
                                        $('#custom_hours_container').show();
                                    }, 500)
                                })()
                            </script>
                        @else
                            <input class="form-control" type="number" step="1" min="1" max="480"
                                   name="aantaluren_custom" value="5">
                            &nbsp;
                            {{ Lang::get('dashboard.minutes') }}
                        @endif
                    </div>
                </div>

            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.category') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="{{ trans('tooltips.producing_category') }}"></i></h4>
                @foreach($categories as $key => $value)
                    <label><input type="radio" name="category_id"
                                  value="{{ $value->category_id }}" {{ (old('category_id') == $value->category_id) ? 'checked' : ($activity->category_id == $value->category_id) ? 'checked' : null }}/><span>{{ $value->category_label }}</span></label>
                @endforeach
            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.work-learn-with') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                                   data-toggle="tooltip" data-placement="bottom"
                                                                   title="{{ trans('tooltips.producing_with') }}"></i>
                </h4>
                <div id="swvcontainer">
                    <label class="expand-click"><input type="radio" name="resource"
                                                       value="persoon" {{ (old('resource') == 'persoon') ? 'checked' : ($activity->res_person_id != null ) ? 'checked' : null }} /><span>{{ Lang::get('activity.person') }}</span></label>
                    <select id="rp_id" name="personsource" class="cond-hidden">
                        @foreach($learningWith as $key => $value)
                            <option value="{{ $value->rp_id }}" {{ (old('personsource') == $value->rp_id) ? 'selected' : ($activity->res_person_id == $value->res_person_id) ? 'selected' : null }}>{{ $value->person_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="solocontainer">
                    <label class="expand-click"><input type="radio" name="resource"
                                                       value="alleen" {{ (old('resource') == 'alleen') ? 'checked' : ($activity->res_person_id == null && $activity->res_material_id == null) ? 'checked' : null }} /><span>{{ Lang::get('activity.alone') }}</span></label>
                </div>
                <div id="internetcontainer">
                    <label class="expand-click"><input type="radio" name="resource"
                                                       value="internet" {{ (old('resource') == 'internet') ? 'checked' : (!old('resource') && $activity->res_material_id == 1) ? 'checked' : null }} /><span>{{ Lang::get('activity.internetsource') }}</span></label>
                    <input class="cond-hidden" maxlength="75" type="text" name="internetsource"
                           value="{{ (old('internetsource') != null) ? old('internetsource') : $activity->res_material_detail }}"
                           placeholder="http://www.bron.domein/"/>
                </div>
                <div id="boekcontainer">
                    <label class="expand-click"><input type="radio" name="resource"
                                                       value="boek" {{ (old('resource') == 'boek') ? 'checked' : (!old('resource') && $activity->res_material_id == 2) ? 'checked' : null }} /><span>{{ Lang::get('activity.book') }}
                            /{{ Lang::get('activity.article') }}</span></label>
                    <input class="cond-hidden" type="text" maxlength="75" name="booksource"
                           value="{{ (old('booksource') != null) ? old('internetsource') : $activity->res_material_detail }}"
                           placeholder="{{ Lang::get('dashboard.name') }}{{ Lang::get('activity.book') }}/{{ Lang::get('activity.article') }}"/>
                </div>
            </div>
            <div class="col-md-2 form-group buttons">
                <h4>{{ Lang::get('activity.status') }} <i class="fa fa-info-circle" aria-hidden="true"
                                                          data-toggle="tooltip" data-placement="bottom"
                                                          title="{{ trans('tooltips.producing_status') }}"></i></h4>
                <label><input type="radio" name="status"
                              value="1" {{ (old('status') == 1) ? 'checked' : ($activity->status_id == 1) ? 'checked' : null }} /><span>{{ Lang::get('activity.finished') }}</span></label>
                <label><input type="radio" name="status"
                              value="2" {{ (old('status') == 2) ? 'checked' : ($activity->status_id == 2) ? 'checked' : null }} /><span>{{ Lang::get('activity.busy') }}</span></label>
                <label><input type="radio" name="status"
                              value="3" {{ (old('status') == 3) ? 'checked' : ($activity->status_id == 3) ? 'checked' : null }} /><span>{{ Lang::get('activity.transferred') }}</span></label>
            </div>
            <div class="col-md-1 form-group buttons">
                <h4>{{ Lang::get('activity.difficulty') }}<i class="fa fa-info-circle" aria-hidden="true"
                                                             data-toggle="tooltip" data-placement="bottom"
                                                             title="{{ trans('tooltips.producing_difficulty') }}"></i>
                </h4>
                <label><input type="radio" name="moeilijkheid"
                              value="1" {{ (old('moeilijkheid') == 1) ? 'checked' : ($activity->difficulty_id == 1) ? 'checked' : null }} /><span>{{ Lang::get('activity.easy') }}</span></label>
                <label><input type="radio" name="moeilijkheid"
                              value="2" {{ (old('moeilijkheid') == 2) ? 'checked' : ($activity->difficulty_id == 2) ? 'checked' : null }} /><span>{{ Lang::get('activity.average') }}</span></label>
                <label><input type="radio" name="moeilijkheid"
                              value="3" {{ (old('moeilijkheid') == 3) ? 'checked' : ($activity->difficulty_id == 3) ? 'checked' : null }} /><span>{{ Lang::get('activity.hard') }}</span></label>
            </div>
            <div class="col-md-1 form-group buttons">
                <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;"
                       value="{{ Lang::get('general.save') }}"/>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop

@extends('layout.HUdefault')
@section('title')
    {{ __('general.internship') }}
@stop
@section('content')
    <?php
    use App\WorkplaceLearningPeriod;
    /** @var WorkplaceLearningPeriod $period */
    ?>
    <div class="container-fluid">
        <a href="{{ url('/profiel') }}">{{ __('elements.profile.internships.backtoprofile') }}</a>
        <br/><br/>
        <!-- Internship Info -->
        <div class="row">
            <!-- Current Internship -->

            {!! Form::open(array(
                'url' => (($period->wplp_id === null) ? route('period-acting-create') : route('period-acting-update', [$period->wplp_id])),
                'data-toggle' => 'validator'))
             !!}
            <div class="col-lg-6">
                @card
                <div class="form-horizontal">
                    <h2>
                        {{ __('elements.profile.internships.current.title') }}
                        @if(Auth::user()->hasCurrentWorkplaceLearningPeriod() && $period->is(Auth::user()->getCurrentWorkplaceLearningPeriod()))
                            {{ __('elements.profile.internships.current.titleadditive') }}
                        @endif
                    </h2>
                    <div class="form-group">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8"><label><input type="checkbox" name="isActive"
                                                            value="1" {{ ((Auth::user()->hasCurrentWorkplaceLearningPeriod() && $period->wplp_id === Auth::user()->getUserSetting('active_internship')->setting_value) || Auth::user()->getUserSetting('active_internship') === null) ? 'checked' : '' }}/> {{ __('elements.profile.internships.activeinternship') }}
                            </label></div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="companyname">{{ __('elements.profile.internships.companyname') }}</label>
                        <div class="col-lg-8">
                            <input name="companyName" maxlength="255" type="text" class="form-control"
                                   placeholder="{{__('elements.profile.internships.companyname')}}"
                                   value="{{ $workplace->wp_name ?? old('companyName') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="companyStreet">{{ __('elements.profile.internships.companystreet') }}</label>
                        <div class="col-lg-8">
                            <input name="companyStreet" maxlength="45" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companystreet') }}"
                                   value="{{ $workplace->street ?? old('companyStreet') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <br/>
                        <div class="col-lg-8 col-lg-offset-4">
                            <input name="companyHousenr" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companyhousenr') }}"
                                   value="{{ $workplace->housenr ?? old('companyHousenr') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="companyPostalcode">{{ __('elements.profile.internships.companylocation') }}</label>
                        <div class="col-lg-3">
                            <input name="companyPostalcode" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companypostalcode') }}"
                                   value="{{ $workplace->postalcode ?? old('companyPostalcode') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-lg-5">
                            <input name="companyLocation" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companylocation') }}"
                                   value="{{ $workplace->town ?? old('companyLocation') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <br/>
                        <div class="col-lg-8 col-lg-offset-4">
                            <input name="companyCountry" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companyCountry') }}"
                                   value="{{ $workplace->country ?? old('companyCountry') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactperson">{{ __('elements.profile.internships.contactperson') }}</label>
                        <div class="col-lg-8">
                            <input name="contactPerson" type="text" maxlength="255" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactperson') }}"
                                   value="{{ $workplace->contact_name ?? old('contactPerson') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactphone">{{ __('elements.profile.internships.contactphone') }}</label>
                        <div class="col-lg-8">
                            <input name="contactPhone" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactphone') }}"
                                   value="{{ $workplace->contact_phone ?? old('contactPhone') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactemail">{{ __('elements.profile.internships.contactemail') }}</label>
                        <div class="col-lg-8">
                            <input name="contactEmail" type="email" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactemail') }}"
                                   value="{{ $workplace->contact_email ?? old('contactEmail') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="numdays">{{ __('elements.profile.internships.numdays') }}</label>
                        <div class="col-lg-8">
                            <input name="numdays" type="number" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.numdays') }}"
                                   value="{{ $period->nrofdays ?? old('numdays') }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="startdate">{{ __('elements.profile.internships.startdate') }}</label>
                        <div class="col-lg-8">
                            <input name="startdate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y', strtotime('-6 months')) }}"
                                   value="{{ $period->startdate ? $period->startdate->format('d-m-Y') : date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="enddate">{{ __('elements.profile.internships.enddate') }}</label>
                        <div class="col-lg-8">
                            <input name="enddate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y') }}"
                                   value="{{ $period->enddate ? $period->enddate->format('d-m-Y') : date('d-m-Y', strtotime('tomorrow')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for='cohort'>{{ __('elements.profile.internships.cohort') }}</label>
                        <div class="col-lg-8">

                            @if($period->cohort === null)
                                <select class="form-control" name="cohort">
                                    @foreach($cohorts as $cohort)
                                        <option @if(old('cohort') === $cohort->id) selected
                                                @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select disabled class="form-control" name="cohort">
                                    <option selected
                                            value="{{ $period->cohort->id }}">{{ $period->cohort->name }}</option>
                                </select>
                            @endif
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-lg-3 col-lg-offset-9">
                            <input type="submit" class="btn btn-info pull-right"
                                   value="{{ __('elements.profile.btnsave') }}"/>
                        </div>
                    </div>
                </div>
                @endcard
            </div>
            <div class="col-lg-6 form-group">
                @card
                <h2>{{ __('elements.profile.internships.current.titleassignment') }}</h2>
                <textarea name="internshipAssignment" rows="19" class="form-control" minlength="15" maxlength="500"
                          data-error="{{ __('elements.profile.labels.internship-assignment-error') }}"
                          required>{{ old('internshipAssignment') ?: $period->description }}</textarea>
                <div class="help-block with-errors"></div>
                @endcard
            </div>
            {!! Form::close() !!}
        </div>
        @if($workplace->wp_name)
            <div class="row">
                <!-- Learning Goals -->
                <div class="col-lg-6">
                    @card
                    {!! Form::open(array('url' => route('learninggoals-update'), 'class' => 'form form-horizontal')) !!}
                    <h3>{{ __('elements.profile.learninggoals.title') }}</h3>
                    <table class="table blockTable">
                        <thead class="blue_tile">
                        <tr>
                            <th>{{ __('elements.profile.learninggoals.goalno') }}</th>
                            <th>{{ __('elements.profile.learninggoals.goalname') }}</th>
                            <th>{{ __('elements.profile.learninggoals.description') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $i = 1; ?>
                        @foreach($learninggoals as $goal)
                            <tr>
                                <td>{{ __('general.learninggoal') }} {{ $i }}</td>
                                <td><input maxlength="45" class="form-control" type="text"
                                           name="learningGoal[{{ $goal->learninggoal_id }}][label]"
                                           value="{{ old('learningGoal.'.$goal->learninggoal_id.'.label', $goal->learninggoal_label) }}"/>
                                </td>
                                <td><textarea class="form-control"
                                              name="learningGoal[{{ $goal->learninggoal_id }}][description]">{{ old('learningGoal.'.$goal->learninggoal_id.'.description', $goal->description) }}</textarea>
                                </td>
                            </tr>
                            <?php ++$i; ?>
                        @endforeach
                        <tr>
                            <td>{{ __('general.new') }} {{ __('general.learninggoal') }}:</td>
                            <td><input maxlength="45" class="form-control" type="text" name="new_learninggoal_name"
                                       placeholder="{{ __('elements.profile.placeholders.learninggoalname') }}"
                                       value="{{ old('new_learninggoal_name') }}"/></td>
                            <td><textarea class="form-control" name="new_learninggoal_description"
                                          placeholder="{{ __('elements.profile.placeholders.learninggoaldescription') }}">{{ old('new_learninggoal_description') }}</textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <input type="submit" class="btn btn-info pull-right"
                           value="{{ __("elements.profile.btnsave") }}"/>
                    <div class="clearfix"></div>
                    {!! Form::close() !!}
                    @endcard
                </div>
            </div>
        @endif
    </div>


    <script type="text/javascript">
        $(document).ready(function () {
            $('.dateInput').datetimepicker({
                locale: 'nl',
                format: 'DD-MM-YYYY',
                minDate: "{{ date('Y-m-d', strtotime('-6 months')) }}",
                useCurrent: true,
            });
        }).on('dp.change', function (e) {
            $(e).attr('value', moment(e.date).format("DD-MM-YYYY"));
        });
    </script>
@stop

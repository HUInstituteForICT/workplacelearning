@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('general.internship') }}
@stop
@section('content')
    <?php
    /** @var \App\WorkplaceLearningPeriod $period */
    ?>
    <div class="container-fluid">
        <a href="{{ url('/profiel') }}">{{ Lang::get('elements.profile.internships.backtoprofile') }}</a>
        <br/><br/>
        <!-- Internship Info -->
        <div class="row">
            <!-- Current Internship -->

            {!! Form::open(array(
                'url' => ((is_null($period->wplp_id)) ? route('period-acting-create') : route('period-acting-update', ['id' => $period->wplp_id])),
                'data-toggle' => 'validator'))
             !!}
            <div class="col-lg-6">
                @card
                <div class="form-horizontal">
                    <h2>
                        {{ Lang::get('elements.profile.internships.current.title') }}
                        @if(Auth::user()->hasCurrentWorkplaceLearningPeriod() && $period->is(Auth::user()->getCurrentWorkplaceLearningPeriod()))
                            {{ Lang::get('elements.profile.internships.current.titleadditive') }}
                        @endif
                    </h2>
                    <div class="form-group">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8"><label><input type="checkbox" name="isActive"
                                                            value="1" {{ ((Auth::user()->hasCurrentWorkplaceLearningPeriod() && $period->wplp_id === Auth::user()->getUserSetting('active_internship')->setting_value) || Auth::user()->getUserSetting('active_internship') === null) ? "checked" : "" }}/> {{ Lang::get('elements.profile.internships.activeinternship') }}
                            </label></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyname', Lang::get('elements.profile.internships.companyname'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyName" maxlength="255" type="text" class="form-control"
                                   placeholder="{{Lang::get('elements.profile.internships.companyname')}}"
                                   value="{{ (is_null($workplace->wp_name)) ? old("companyName") : $workplace->wp_name }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyStreet', Lang::get('elements.profile.internships.companystreet'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyStreet" maxlength="45" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.companystreet') }}"
                                   value="{{ (is_null($workplace->street)) ? old("companyStreet") : $workplace->street }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <br/>
                        <div class="col-lg-8 col-lg-offset-4">
                            <input name="companyHousenr" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.companyhousenr') }}"
                                   value="{{ (is_null($workplace->housenr)) ? old("companyHousenr") : $workplace->housenr }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyPostalcode', Lang::get('elements.profile.internships.companylocation'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-3">
                            <input name="companyPostalcode" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.companypostalcode') }}"
                                   value="{{ (is_null($workplace->postalcode)) ? old("companyPostalcode") : $workplace->postalcode }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-lg-5">
                            <input name="companyLocation" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.companylocation') }}"
                                   value="{{ (is_null($workplace->town)) ? old("companyLocation") : $workplace->town }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <br/>
                        <div class="col-lg-8 col-lg-offset-4">
                            <input name="companyCountry" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.companyCountry') }}"
                                   value="{{ (is_null($workplace->country)) ? old("companyCountry") : $workplace->country }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', Lang::get('elements.profile.internships.contactperson'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactPerson" type="text" maxlength="255" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.contactperson') }}"
                                   value="{{ (is_null($workplace->contact_name)) ? old("contactPerson") : $workplace->contact_name }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', Lang::get('elements.profile.internships.contactphone'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactPhone" type="text" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.contactphone') }}"
                                   value="{{ (is_null($workplace->contact_phone)) ? old("contactPhone") : $workplace->contact_phone }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', Lang::get('elements.profile.internships.contactemail'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactEmail" type="email" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.contactemail') }}"
                                   value="{{ (is_null($workplace->contact_email)) ? old("contactEmail") : $workplace->contact_email }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('numdays', Lang::get('elements.profile.internships.numdays'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="numdays" type="number" class="form-control"
                                   placeholder="{{ Lang::get('elements.profile.internships.numdays') }}"
                                   value="{{ (is_null($period->nrofdays)) ? old("numdays") : $period->nrofdays }}"
                                   required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', Lang::get('elements.profile.internships.startdate'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="startdate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y', strtotime('-6 months')) }}"
                                   value="{{ $period->startdate ? $period->startdate->format('d-m-Y') : date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', Lang::get('elements.profile.internships.enddate'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="enddate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y') }}"
                                   value="{{ $period->enddate ? $period->enddate->format('d-m-Y') : date('d-m-Y', strtotime('tomorrow')) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('cohort', Lang::get('elements.profile.internships.cohort'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">

                            @if($period->cohort === null)
                                <select class="form-control" name="cohort">
                                    @foreach($cohorts as $cohort)
                                        <option @if(old('cohort') === $cohort->id) selected
                                                @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select readonly="true" disabled class="form-control" name="cohort">
                                    <option selected
                                            value="{{ $period->cohort->id }}">{{ $period->cohort->name }}</option>
                                </select>
                            @endif
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-lg-3 col-lg-offset-9">
                            <input type="submit" class="btn btn-info pull-right"
                                   value="{{ Lang::get('elements.profile.btnsave') }}"/>
                        </div>
                    </div>
                </div>
                @endcard
            </div>
            <div class="col-lg-6 form-group">
                @card
                <h2>{{ Lang::get('elements.profile.internships.current.titleassignment') }}</h2>
                <textarea name="internshipAssignment" rows="19" class="form-control" minlength="15" maxlength="500"
                          data-error="{{ Lang::get('elements.profile.labels.internship-assignment-error') }}"
                          required>{{ (old('internshipAssignment')) ? old('internshipAssignment') : $period->description }}</textarea>
                <div class="help-block with-errors"></div>
                @endcard
            </div>
            {!! Form::close() !!}
        </div>
        @if(!is_null($workplace->wp_name))
            <div class="row">
                <!-- Learning Goals -->
                <div class="col-lg-6">
                    @card
                    {!! Form::open(array('url' => route('learninggoals-update'), 'class' => 'form form-horizontal')) !!}
                    <h3>{{ Lang::get('elements.profile.learninggoals.title') }}</h3>
                    <table class="table blockTable">
                        <thead class="blue_tile">
                        <tr>
                            <th>{{ Lang::get('elements.profile.learninggoals.goalno') }}</th>
                            <th>{{ Lang::get('elements.profile.learninggoals.goalname') }}</th>
                            <th>{{ Lang::get('elements.profile.learninggoals.description') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($learninggoals as $goal)
                            <tr>
                                <td>{{ Lang::get('general.learninggoal') }} {{ $i }}</td>
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
                            <td>{{ __('general.new') }} {{ Lang::get('general.learninggoal') }}:</td>
                            <td><input maxlength="45" class="form-control" type="text" name="new_learninggoal_name"
                                       placeholder="{{ Lang::get('elements.profile.placeholders.learninggoalname') }}"
                                       value="{{ old('new_learninggoal_name') }}"/></td>
                            <td><textarea class="form-control" name="new_learninggoal_description"
                                          placeholder="{{ Lang::get('elements.profile.placeholders.learninggoaldescription') }}">{{ old('new_learninggoal_description') }}</textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <input type="submit" class="btn btn-info pull-right"
                           value="{{ Lang::get("elements.profile.btnsave") }}"/>
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

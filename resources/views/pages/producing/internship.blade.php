<?php
/**
 * This file (internship.blade.php) was created on 06/22/2016 at 23:59.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>

@extends('layout.HUdefault')
@section('title')
    {{ __('general.internship') }}
@stop
@section('content')
    <div class="container-fluid">
        <a href="{{ url('/profiel') }}">{{ __('elements.profile.internships.backtoprofile') }}</a>
        <br/><br/>
        <!-- Internship Info -->
        <div class="row">
            <!-- Current Internship -->


            {!! Form::open(array(
                'url' => (($period->wplp_id === null) ? route('period-producing-create') : route('period-producing-update', [$period->wplp_id])),
                'data-toggle' => 'validator'))
             !!}
            <div class="col-lg-5">
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
                                   value="{{ $workplace->wp_name ?? old('companyName') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="companyStreet">{{ __('elements.profile.internships.companystreet') }}</label>
                        <div class="col-lg-8">
                            <input name="companyStreet" maxlength="45" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companystreet') }}"
                                   value="{{ $workplace->street ?? old('companyStreet') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>

                        <label class="col-lg-4 control-label" for="companyHousenr">{{ __('elements.profile.internships.companyhousenr') }}</label>
                        <div class="col-lg-8">
                            <input name="companyHousenr" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companyhousenr') }}"
                                   value="{{ $workplace->housenr ?? old('companyHousenr') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="companyPostalcode">{{ __('elements.profile.internships.companypostalcode') }}</label>
                        <div class="col-lg-8">
                            <input name="companyPostalcode" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companypostalcode') }}"
                                   value="{{ $workplace->postalcode ?? old('companyPostalcode') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="companyLocation">{{ __('elements.profile.internships.companylocation') }}</label>
                        <div class="col-lg-8">
                            <input name="companyLocation" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companylocation') }}"
                                   value="{{ $workplace->town ?? old('companyLocation') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="companyCountry">{{ __('elements.profile.internships.companyCountry') }}</label>
                        <div class="col-lg-8">
                            <input name="companyCountry" maxlength="255" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.companyCountry') }}"
                                   value="{{ $workplace->country ?? old('companyCountry') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactperson">{{ __('elements.profile.internships.contactperson') }}</label>
                        <div class="col-lg-8">
                            <input name="contactPerson" maxlength="255" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactperson') }}"
                                   value="{{ $workplace->contact_name ?? old('contactPerson') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactphone">{{ __('elements.profile.internships.contactphone') }}</label>
                        <div class="col-lg-8">
                            <input name="contactPhone" type="text" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactphone') }}"
                                   value="{{ $workplace->contact_phone ?? old('contactPhone') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="contactemail">{{ __('elements.profile.internships.contactemail') }}</label>
                        <div class="col-lg-8">
                            <input name="contactEmail" type="email" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.contactemail') }}"
                                   value="{{ $workplace->contact_email ?? old('contactEmail') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="numdays">{{ __('elements.profile.internships.numdays') }}</label>
                        <div class="col-lg-8">
                            <input name="numdays" type="number" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.numdays') }}"
                                   value="{{ $period->nrofdays ?? old('numdays') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="numhours">{{ __('elements.profile.internships.numhours') }}</label>
                        <div class="col-lg-8">
                            <input name="numhours" step="0.5" type="number" max="24" class="form-control"
                                   placeholder="{{ __('elements.profile.internships.numhours') }}"
                                   value="{{ $period->hours_per_day ?? old('numhours') }}" required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="startdate">{{ __('elements.profile.internships.startdate') }}</label>
                        <div class="col-lg-8">
                            <input name="startdate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y', strtotime('-6 months')) }}"
                                   value="{{ ($period->startdate) ? $period->startdate->format('d-m-Y') : date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for="enddate">{{ __('elements.profile.internships.enddate') }}</label>
                        <div class="col-lg-8">
                            <input name="enddate" type="text" class="form-control dateInput"
                                   min="{{ date('d-m-Y') }}"
                                   value="{{(($period->enddate) ? $period->enddate->format('d-m-Y') : date('d-m-Y', strtotime('tomorrow'))) }}">
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"
                               for='cohort'>{{ __('elements.profile.internships.cohort') }}</label>
                        <div class="col-lg-8">
                            <select @if($period->cohort) disabled @endif class="form-control"
                                    name="cohort">
                                @foreach($cohorts as $cohort)

                                    @if($period->cohort === null)
                                        <option @if(old('cohort') === $cohort->id) selected
                                                @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @else
                                        <option @if($period->cohort->id === $cohort->id) selected
                                                @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @endif
                                @endforeach
                            </select>
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
            <div class="col-lg-7 form-group">
                @card
                <h2>{{ __('elements.profile.internships.current.titleassignment') }}</h2>
                <textarea name="internshipAssignment" rows="19" class="form-control" minlength="15" maxlength="500"
                          data-error="{{ __('elements.profile.labels.internship-assignment-error') }}"
                          required>{{ (old('internshipAssignment')) ?: $period->description }}</textarea>
                <div class="help-block with-errors"></div>
                @endcard
            </div>
            {!! Form::close() !!}
        </div>
        @if($workplace->wp_name !== null)
            <div class="row">
                <!-- Categories -->
                <div class="col-lg-5">
                    @card
                    {!! Form::open(array('url' => URL::to('categorie/update', ['id'=>$period->wplp_id], true), 'class' => 'form form-horizontal')) !!}
                    <h3>{{ __('elements.profile.categories.title') }}</h3>
                    <table class="table blockTable">
                        <thead class="blue_tile">
                        <tr>
                            <th>{{ __('elements.profile.categories.internshipname') }}</th>
                            <th>{{ __('elements.profile.categories.categoryname') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <input type="hidden" name="cat[{{ $cat->category_id  }}][wplp_id]"
                                       value="{{ $cat->wplp_id }}"/>
                                <input type="hidden" name="cat[{{ $cat->category_id }}][cg_id]"
                                       value="{{ $cat->category_id }}"/>
                                <td>{{ $workplace->wp_name
                            .' ('. date('d-m-Y', strtotime($period->startdate))
                            . ' - ' . date('d-m-Y', strtotime($period->enddate)). ')' }}</td>
                                <td><input name="cat[{{ $cat->category_id }}][cg_label]"
                                           value="{{
                                        old('category['. $cat->category_id .'][cg_label]')
                                        ?: $cat->category_label
                                        }}"
                                    /></td>
                            </tr>
                        @endforeach
                        <tr>
                            <input type="hidden" name="newcat[0][wplp_id]" value="{{ $period->wplp_id }}"/>
                            <input type="hidden" name="newcat[0][cg_id]" value="-1"/>
                            <td>{{ $workplace->wp_name }}
                                <br/>{{ '('. date('d-m-Y', strtotime($period->startdate)). ' - ' . date('d-m-Y', strtotime($period->enddate)). ')' }}
                            </td>
                            <td><input name="newcat[0][cg_label]"
                                       class="form-control"
                                       placeholder="{{ __('elements.profile.placeholders.categoryname') }}"
                                       value="{{ old('cat[0][cg_label]') }}"/></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="col-lg-3 col-lg-offset-9">
                            <input type="submit" class="btn btn-info pull-right"
                                   value="{{ __('elements.profile.btnsave') }}"/>
                        </div>
                    </div>
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

<?php
/**
 * This file (internship.blade.php) was created on 06/22/2016 at 23:59.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>

@extends('layout.HUdefault')
@section('title')
    Stage
@stop
@section('content')
    <div class="container-fluid">
        <!-- Internship Info -->
        <div class="row">
            <!-- Current Internship -->
            <div class="col-md-12 well-sm">
                <a href="{{ url('/profiel') }}">&lt;-  {{ Lang::get('elements.profile.internships.backtoprofile') }}</a>
            </div>

            @if(count($errors) > 0 || session()->has('success'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                            <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                        </div>
                    </div>
                </div>
            @endif


            {!! Form::open(array('url' => (($period->stud_stid != null) ? URL::to('stageperiode/update/'.$period->stud_stid, array(), true) : URL::to('stageperiode/update/0', array(), true)), 'data-toggle' => 'validator')) !!}
            <div class="col-md-5">
                <div class="form-horizontal well">
                    <h2>
                        {{ Lang::get('elements.profile.internships.current.title') }}
                        @if(Auth::user()->getCurrentInternshipPeriod() && $period->stud_stid == Auth::user()->getCurrentInternshipPeriod()->stud_stid)
                            {{ Lang::get('elements.profile.internships.current.titleadditive') }}
                        @endif
                    </h2>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8"><label><input type="checkbox" name="isActive" value="1" {{ ((Auth::user()->getCurrentInternshipPeriod() != null && $period->stud_stid == Auth::user()->getUserSetting('active_internship')->setting_value) || Auth::user()->getUserSetting('active_internship') == NULL) ? "checked" : "" }}/> {{ Lang::get('elements.profile.internships.activeinternship') }}</label></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyname', Lang::get('elements.profile.internships.companyname'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="companyName" pattern="[a-zA-Z0-9 ,.()-]{3,255}" type="text" class="form-control" placeholder="{{Lang::get('elements.profile.internships.companyname')}}" value="{{ ($period->getInternship() == null) ? old("companyName") : $period->getInternship()->bedrijfsnaam }}" data-error="{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z ,.()-" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companylocation', Lang::get('elements.profile.internships.companylocation'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="companyLocation" pattern="[a-zA-Z0-9 ()-]{3,255}" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companylocation') }}" value="{{ ($period->getInternship() == null) ? old("companyLocation") : $period->getInternship()->plaats }}" data-error="{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z ()-" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', Lang::get('elements.profile.internships.contactperson'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="contactPerson" type="text" pattern="[a-zA-Z .,()-]{3,255}" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactperson') }}" value="{{ ($period->getInternship() == null) ? old("contactPerson") : $period->getInternship()->contactpersoon }}" data-error="{{ Lang::get('elements.general.mayonlycontain') }} a-zA-Z .,()-" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', Lang::get('elements.profile.internships.contactphone'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="contactPhone" type="text" pattern="[0-9]{2,3}-?[0-9]{7,8}" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactphone') }}" value="{{ ($period->getInternship() == null) ? old("contactPhone") : $period->getInternship()->telefoon }}" data-error="{{ Lang::get('elements.general.mayonlycontain') }} XX(X)(-)XXXXXXX(X)" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', Lang::get('elements.profile.internships.contactemail'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="contactEmail" type="email" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactemail') }}" value="{{ ($period->getInternship() == null) ? old("contactEmail") : $period->getInternship()->contactemail }}" data-error="Geen geldig e-mail address." required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('numhours', Lang::get('elements.profile.internships.numhours'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            <input name="numhours" type="number" pattern="[0-9]{1,5}" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.numhours') }}" value="{{ ($period->aantaluren == 0) ? old("numhours") : $period->aantaluren }}" data-error="Dit veld is verplicht."  required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', Lang::get('elements.profile.internships.startdate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-6">
                            <input name="startdate" type="date" class="form-control" min="{{ date("Y-m-d", strtotime("-6 months")) }}" value="{{ date("Y-m-d", ($period->startdatum) ? strtotime($period->startdatum) : strtotime("now")) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', Lang::get('elements.profile.internships.enddate'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-6">
                            <input name="enddate" type="date" class="form-control" min="{{ date("Y-m-d", strtotime("now")) }}" value="{{ date("Y-m-d", ($period->einddatum) ? strtotime($period->einddatum) : strtotime("tomorrow")) }}">
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 well form-group">
                <h2>{{ Lang::get('elements.profile.internships.current.titleassignment') }}</h2>
                <textarea name="internshipAssignment" rows="19" class="form-control" minlength="15" maxlenght="500" data-error="Dit veld moet minimaal 15 characters hebben en kan maximaal 500 characters bevatten. Alleen de volgende characters zijn toegestaan: [0-9a-zA-Z -_.,()]" required>{{ (old('internshipAssignment')) ? old('internshipAssignment') : $period->opdrachtomschrijving }}</textarea>
                <div class="help-block with-errors"></div>
            </div>
            {!! Form::close() !!}
        </div>
        @if($period->getInternship() != null)
        <div class="row">
            <!-- Categories -->
            <div class="col-lg-5">
                {!! Form::open(array('url' => URL::to('categorie/update/'.$period->stud_stid, array(), true), 'class' => 'form form-horizontal well')) !!}
                <h3>{{ Lang::get('elements.profile.categories.title') }}</h3>
                <table class="table blockTable">
                    <thead class="blue_tile">
                    <tr>
                        <th>{{ Lang::get('elements.profile.categories.internshipname') }}</th>
                        <th>{{ Lang::get('elements.profile.categories.categoryname') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($period->categorieen()->get() as $cat)
                            <tr>
                                <input type="hidden" name="cat[{{ $cat->cg_id  }}][ss_id]" value="{{ $cat->ss_id }}" />
                                <input type="hidden" name="cat[{{ $cat->cg_id }}][cg_id]" value="{{ $cat->cg_id }}" />
                                <td>{{ $period->getInternship()->bedrijfsnaam
                            ." (". date('d-m-Y', strtotime($period->getStartDate()))
                            . " - " . date('d-m-Y', strtotime($period->getEndDate())). ")" }}</td>
                                <td><input name="cat[{{ $cat->cg_id }}][cg_value]"
                                           value="{{
                                        old("category[". $cat->cg_id ."][cg_value]")
                                        ? old("category[". $cat->cg_id ."][cg_value]")
                                        : $cat->cg_value
                                        }}"
                                    /></td>
                            </tr>
                        @endforeach
                        <tr>
                            <input type="hidden" name="newcat[-1][ss_id]" value="{{ $period->stud_stid }}" />
                            <input type="hidden" name="newcat[-1][cg_id]" value="-1" />
                            <td>{{ $period->getInternship()->getCompanyName() }}<br />{{ "(". date('d-m-Y', strtotime($period->getStartDate())). " - " . date('d-m-Y', strtotime($period->getEndDate())). ")" }}</td>
                            <td><input name="newcat[-1][cg_value]" placeholder="{{ Lang::get('elements.profile.placeholders.categoryname') }}" value="{{ old('cat[-1][cg_value]') }}" /></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                {!! Form::close() !!}
            </div>

            <!-- Werkverbanden -->
            <div class="col-lg-5">
                {!! Form::open(array('url' => URL::to('samenwerkingsverband/update/'.$period->stud_stid, array(), true), 'class' => 'form form-horizontal well')) !!}
                <h3>{{ Lang::get('elements.profile.cooperations.title') }}</h3>
                <table class="table blockTable">
                    <thead class="blue_tile">
                    <tr>
                        <th>{{ Lang::get('elements.profile.cooperations.internshipname') }}</th>
                        <th>{{ Lang::get('elements.profile.cooperations.cooperationname') }}</th>
                        <th>{{ Lang::get('elements.profile.cooperations.cooperationdesc') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($period->samenwerkingsverbanden()->get() as $swv)
                            <tr>
                                <input type="hidden" name="swv[{{ $swv->swv_id }}][ss_id]" value="{{ $period->stud_stid }}" />
                                <input type="hidden" name="swv[{{ $swv->swv_id }}][swv_id]" value="{{ $swv->swv_id }}" />
                                <td>{{ $period->getInternship()->bedrijfsnaam }}<br />{{ "(". date('d-m-Y', strtotime($period->startdatum)). " - " . date('d-m-Y', strtotime($period->einddatum)). ")" }}</td>
                                <td><input name="swv[{{ $swv->swv_id }}][value]" placeholder="{{ Lang::get('elements.profile.placeholders.cooperationname') }}"value="{{ old("swv[". $swv->swv_id ."][value]") ? old("swv[". $swv->swv_id ."][value]") : $swv->swv_value }}" /></td>
                                <td><input name="swv[{{ $swv->swv_id }}][omschrijving]" placeholder="{{ Lang::get('elements.profile.placeholders.cooperationdesc') }}"value="{{ old("swv[". $swv->swv_id ."][omschrijving]") ? old("swv[". $swv->swv_id ."][omschrijving]") : $swv->swv_omschrijving }}" /></td>
                            </tr>
                        @endforeach
                        <tr>
                            <input type="hidden" name="newswv[-1][ss_id]" value="{{ $period->stud_stid }}" />
                            <input type="hidden" name="newswv[-1][swv_id]" value="-1" />
                            <td>{{ $period->getInternship()->getCompanyName() }}<br />{{ "(". date('d-m-Y', strtotime($period->getStartDate())). " - " . date('d-m-Y', strtotime($period->getEndDate())). ")" }}</td>
                            <td><input name="newswv[-1][value]" value="" /></td>
                            <td><input name="newswv[-1][omschrijving]" value="" /></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                {!! Form::close() !!}
            </div>
        </div>
        @endif
    </div>
@stop

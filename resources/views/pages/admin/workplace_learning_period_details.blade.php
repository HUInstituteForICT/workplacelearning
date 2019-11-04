@extends('layout.HUdefault')
@section('title')
    Student details
@stop
@section('content')
    <?php
    use App\WorkplaceLearningPeriod;
    /** @var WorkplaceLearningPeriod $wplp */
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <a href="{{ route('admin-student-details', ['student' => $wplp->student]) }}">
                    Back to student
                </a>

                <br/><br/>

                @card
                <h3>Edit details</h3>

                <form method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $wplp->wplp_id }}"/>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Workplace</h4>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="workplace[name]"
                                       value="{{ old('workplace.name', $wplp->workplace->wp_name) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Street</label>
                                <input type="text" class="form-control" name="workplace[street]"
                                       value="{{ old('workplace.street', $wplp->workplace->street) }}"/>
                            </div>

                            <div class="form-group">
                                <label>House number</label>
                                <input type="text" class="form-control" name="workplace[housenr]"
                                       value="{{ old('workplace.housenr', $wplp->workplace->housenr) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Postal code</label>
                                <input type="text" class="form-control" name="workplace[postalcode]"
                                       value="{{ old('workplace.postalcode', $wplp->workplace->postalcode) }}"/>
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" name="workplace[town]"
                                       value="{{ old('workplace.town', $wplp->workplace->town) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control" name="workplace[country]"
                                       value="{{ old('workplace.country', $wplp->workplace->country) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Person</label>
                                <input type="text" class="form-control" name="workplace[person]"
                                       value="{{ old('workplace.person', $wplp->workplace->contact_name) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="workplace[phone]"
                                       value="{{ old('workplace.phone', $wplp->workplace->contact_phone) }}"/>
                            </div>

                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="text" class="form-control" name="workplace[email]"
                                       value="{{ old('workplace.email', $wplp->workplace->contact_email) }}"/>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <h4>Workplace Learning Period</h4>

                            <div class="form-group">
                                <label>Duration in days</label>
                                <input type="text" class="form-control" name="workplaceLearningPeriod[days]"
                                       value="{{ old('workplaceLearningPeriod.days', $wplp->nrofdays) }}"/>
                            </div>

                            <div class="form-group">
                                <label>Hours per day</label>
                                <input type="text" class="form-control" name="workplaceLearningPeriod[hours_per_day]"
                                       value="{{ old('workplaceLearningPeriod.hours_per_day', $wplp->hours_per_day) }}"/>
                                @if($wplp->cohort->educationProgram->educationprogramType->isActing())
                                    <span class="help-block">
                                        This is an acting internship, updating the hours per day does nothing for the student.
                                    </span>
                                @endif
                            </div>

                            @if($canUpdateCohort)
                                <div class="form-group">
                                    <label>Cohort</label>
                                    <select class="form-control" name="workplaceLearningPeriod[cohort_id]">
                                        @foreach($cohorts as $cohort)
                                            <option @if(( (int) old('workplaceLearningPeriod.cohort_id', $wplp->cohort_id)) === $cohort->id) selected
                                                    @endif
                                                    value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group">
                                    <label>Cohort</label>
                                    <select class="form-control" disabled>
                                        <option>{{ $wplp->cohort->name }}</option>
                                    </select>
                                    <p class="form-control-static">
                                        Student has registered activities, you cannot update the cohort
                                    </p>
                                </div>
                            @endif


                            <div class="form-group">
                                <label>
                                    <input type="checkbox"
                                           name="workplaceLearningPeriod[is_in_analytics]" value="1"
                                           @if($wplp->is_in_analytics) checked="checked" @endif
                                    />
                                    In analytics
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-md-offset-8">
                            <input type="submit" class="btn btn-info btn-block" value="{{ __('general.save') }}">
                        </div>
                    </div>

                </form>
                @endcard
            </div>
        </div>
    </div>
@stop


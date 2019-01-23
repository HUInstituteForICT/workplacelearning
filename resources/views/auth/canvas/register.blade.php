@extends('layout.HUlogin')
@section('title')
    {{__('elements.registration.title')}}
@stop
@section('content')
    <div class="jumbotron vertical-center" style="background-color: #FFF;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ __('elements.registration.title') }}</div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    {{ Form::open() }}
                                    {{ csrf_field() }}

                                    <div>{{ __('Je Werkplekleren account is bijna aangemaakt, het is enkel nog nodig om je studentennummer en opleiding door te geven.') }}</div>

                                    <div class="form-group{{ $errors->has('studentnummer') ? ' has-error' : '' }}">
                                        <label class="control-label">{{ __('elements.registration.labels.studentnr') }}
                                            <span class="required"></span></label>

                                        <input type="text" class="form-control"
                                               placeholder="{{__('elements.registration.placeholders.studentnr') }}"
                                               name="studentnr" value="{{ old('studentnr') }}">

                                        @if ($errors->has('studentnr'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('studentnr') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                                        <label class="control-label">{{ __('elements.registration.labels.education') }}</label>

                                        <select name="education" class="form-control" id="educationSelect">
                                            @foreach(\App\EducationProgram::where('disabled', '=', 0)->get() as $program)
                                                <option value="{{ $program->ep_id }}" {{ ((int)old('education') === $program->ep_id) ? 'selected' : null }}> {{ $program->ep_name }}</option>
                                            @endforeach
                                            <option value="other">{{ __('activity.other') }}</option>
                                        </select>

                                        @if ($errors->has('answer'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('education') }}</strong>
                                            </span>
                                        @endif
                                        <span class="alert alert-danger help-block" style="display: none;" id="otherWarning">
                                            {{ __('Je opleiding staat er niet tussen? Neem dan contact op met je stage coördinator.') }}
                                        </span>


                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="registerButton">
                                            <i class="fa fa-btn fa-user"></i>{{ __('elements.registration.buttons.register') }}
                                        </button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-lg-offset-5">
                                    {{ Form::open(['url'=>route('localeswitcher'), 'id' => 'localeSwitcherForm']) }}
                                    {{ Form::hidden('previousPage', URL::current()) }}
                                    {!! Form::select('locale', \App\Student::$locales, Session::get('locale', 'nl'), ['id' => 'localeSwitcher', 'class' => ''] )!!}
                                    <script>
                                        (function () {
                                            $('#localeSwitcher').on('change', function () {
                                                $('#localeSwitcherForm').submit();
                                            })
                                        })()
                                    </script>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#educationSelect').on('change', function (e) {
            checkSelectedEducation();
        });

        function checkSelectedEducation() {
            if ($('#educationSelect option:selected').val() === 'other') {
                $('#otherWarning').show();
                $('#registerButton').prop('disabled', true);

            } else {
                $('#otherWarning').hide();
                $('#registerButton').prop('disabled', false);
            }
        }

        checkSelectedEducation();
    </script>
@endsection

@extends('layout.HUlogin')
@section('title')
    Account Registration
@stop
@section('content')
    <div class="jumbotron vertical-center" style="background-color: #FFF;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ __('elements.registration.title') }}</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{  URL::to('/register', array(), true) }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('studentnummer') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.studentnr') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{__('elements.registration.placeholders.studentnr') }}" name="studentnr" value="{{ old('studentnr') }}">

                                        @if ($errors->has('studentnr'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('studentnr') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.firstname') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{__('elements.registration.placeholders.firstname') }}" name="firstname" value="{{ old('firstname') }}">

                                        @if ($errors->has('firstname'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.lastname') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{__('elements.registration.placeholders.lastname') }}" name="lastname" value="{{ old('lastname') }}">

                                        @if ($errors->has('lastname'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.email') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="email" class="form-control" placeholder="{{__('elements.registration.placeholders.email') }}" name="email" value="{{ old('email') }}">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.password') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="password" class="form-control" placeholder="{{__('elements.registration.placeholders.password') }}" name="password">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.password_confirm') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="password" class="form-control" placeholder="{{__('elements.registration.placeholders.password') }}" name="password_confirmation">

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.education') }}</label>

                                    <div class="col-md-6">
                                        <select name="education" class="form-control">
                                          @foreach(\App\EducationProgram::where('disabled', '=', 0)->get() as $program)
                                                <option value="{{ $program->ep_id }}" {{ (old('education') == $program->ep_id) ? 'selected' : null }}> {{ $program->ep_name }}</option>
                                          @endforeach
                                        </select>

                                        @if ($errors->has('answer'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('education') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('secret') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ __('elements.registration.labels.secret') }}<span class="required"></span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{__('elements.registration.placeholders.secret') }}" name="secret" value="{{ old('secret') }}">

                                        @if ($errors->has('secret'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('secret') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <p style="font-size: 10px;"><input class="required" name="privacy" type="checkbox" value="1"></input>
                                        {{ __('elements.registration.privacyagreement.header') }} <a href="/assets/pdf/privacyverklaring.pdf" target="_blank">{{ __('elements.registration.privacyagreement.word') }}</a>:
                                        <ul>
                                        <li style="font-size: 10px;">{{ __('elements.registration.privacyagreement.bullet1') }};</li>
                                        <li style="font-size: 10px;">{{ __('elements.registration.privacyagreement.bullet2') }};</li>
                                        <li style="font-size: 10px;">{{ __('elements.registration.privacyagreement.bullet3') }}.</li>
                                        </ul>
                                        </p>
                                        @if ($errors->has('privacy'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('privacy') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-user"></i>{{ __('elements.registration.buttons.register') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <a href="{{ url('/login') }}">{{ __('auth.to_login') }}</a>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-2 col-lg-offset-5">
                                    {{ Form::open(["url"=>route('localeswitcher'), "id" => "localeSwitcherForm"]) }}
                                    {{ Form::hidden('previousPage', URL::current()) }}
                                    {!! Form::select('locale', \App\Student::$locales, Session::get('locale', 'nl'), ["id" => "localeSwitcher", "class" => ""] )!!}
                                    <script>
                                        (function(){$('#localeSwitcher').on('change', function() {
                                            $('#localeSwitcherForm').submit();
                                        })})()
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
@endsection

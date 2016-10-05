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
                <div class="panel-heading">{{ Lang::get('elements.registration.title') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{  URL::to('/register', array(), true) }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('studentnummer') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.studentnr') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.studentnr') }}" name="studentnummer" value="{{ old('studentnummer') }}">

                                @if ($errors->has('studentnummer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('studentnummer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.firstname') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.firstname') }}" name="firstname" value="{{ old('firstname') }}">

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.lastname') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.lastname') }}" name="lastname" value="{{ old('lastname') }}">

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.gender.text') }}</label>

                            <div class="col-md-6">
                                <label class="radio-inline">
                                    {{ Form::radio('gender', 'male', ((old('gender') == "male") || empty(old('gender')))) }} {{ Lang::get('elements.registration.labels.gender.male') }}
                                </label>
                                <label class="radio-inline">
                                    {{ Form::radio('gender', 'female', (old('gender') == "female")) }} {{ Lang::get('elements.registration.labels.gender.female') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birthdate') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.birthdate') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="birthdate" max="{{ date("Y\\-m\\-d", strtotime("-17 years")) }}" value="{{ date("Y\\-m\\-d", ((!empty(old('birthdate'))) ? strtotime(old('birthdate')) : strtotime("-17 years"))) }}">
                                @if ($errors->has('birthdate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birthdate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.email') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.email') }}" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.phone') }}</label>

                            <div class="col-md-6">
                                <input type="tel" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.phone') }}" name="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.password') }}" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.password_confirm') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.password') }}" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.answer') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.answer') }}" name="answer" value="{{ old('answer') }}">

                                @if ($errors->has('answer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('secret') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ Lang::get('elements.registration.labels.secret') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{Lang::get('elements.registration.placeholders.secret') }}" name="secret" value="{{ old('secret') }}">

                                @if ($errors->has('secret'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>{{ Lang::get('elements.registration.buttons.register') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <a href="{{ url('/login') }}">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

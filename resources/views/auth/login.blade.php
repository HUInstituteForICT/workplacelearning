@extends('layout.HUlogin')
@section('title')
    Login
@stop
@section('content')
    <div class="container">
        <div class="row main">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    <div id="logo-container"></div>
                </div>
            </div>
            <div class="main-login main-center">
                <form class="form-horizontal" method="post" action="/login">
                    <div class="form-group">
                        <div class="cols-sm-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                                <input class="form-control" placeholder="student@student.com" type="text" name="email" value="{{ old('email') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                                <input class="form-control" type="password" name='password' placeholder="{{ Lang::get('elements.profile.labels.password') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-def btn-block" value="Login" />
                    </div>
                    <div class="form-group text-center">
                        <a href="{{ url('/register') }}">{{ Lang::get('elements.registration.buttons.register') }}</a>&nbsp;|&nbsp;<a href="{{ url('/password/reset') }}">{{ Lang::get('passwords.reset_password') }}</a>
                    </div>

                </form>
                <div class="row">
                    <div class="col-md-2 col-lg-offset-3">
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
                @if(count($errors) > 0 || session()->has('success'))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                                <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
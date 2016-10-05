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
                <form class="form-horizontal" method="post" action="#">
                    <div class="form-group">
                        <div class="cols-sm-11">
                            <div class="input-group">
                                {{ csrf_field() }}
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input class="form-control" placeholder="student@student.hu.nl" type="text" name="email" value="{{ old('email') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                                <input class="form-control" type="password" name='password' placeholder="Wachtwoord"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-def btn-block" value="Login" />
                    </div>
                    <div class="form-group text-center">
                        <a href="{{ url('/register') }}">Registreer Account</a>&nbsp;|&nbsp;<a href="{{ url('/reset/password') }}">Reset Wachtwoord</a>
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
                </form>
            </div>
        </div>
    </div>


@endsection
@extends('layout.HUlogin')
@section('title')
    Reset Wachtwoord
@stop
@section('content')
<div class="jumbotron vertical-center" style="background-color: #FFF;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset</div>
                    <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/password/reset', array(), true) }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Controlevraag: Waar ben je geboren?</label>
                            <div class="col-md-6">
                                <input type="text" autocomplete="noway" class="form-control" name="answer" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input type="email" autocomplete="noway" class="form-control" name="email" value="{{ $email or old('email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nieuw Wachtwoord:</label>
                            <div class="col-md-6">
                                <input type="password" autocomplete="noway" class="form-control" name="password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nieuw Wachtwoord (Bevestiging):</label>
                            <div class="col-md-6">
                                <input type="password" autocomplete="noway" class="form-control" name="password_confirmation" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>{{ Lang::get('elements.registration.buttons.reset') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <a href="{{ url('/login') }}">Back to Login</a>
                        </div>
                    </form>
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
        </div>
</div>
@endsection

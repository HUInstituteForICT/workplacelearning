@extends('layout.HUlogin')
@section('title')
    {{ __('auth.verification.title') }}
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


                <h3>{{ __('auth.verification.title') }}</h3>

                <div>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.verification.resent') }}
                        </div>
                    @endif

                    {{ __('auth.verification.message') }}
                        <br/>
                    {{ __('auth.verification.not-received') }}, <a
                            href="{{ route('verification.resend') }}">{{ __('auth.verification.again') }}</a>.


                    <br/><br/>

                    <a href="{{ action('Auth\LoginController@logout') }}">{{ __('elements.sidebar.labels.logout') }}</a>
                </div>


            </div>
        </div>
    </div>
@endsection



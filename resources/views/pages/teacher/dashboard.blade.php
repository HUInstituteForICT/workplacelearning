@extends('layout.HUdefault')
@section('title')
    Dashboard
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ __('dashboard.title') }}</h1>

                <p>Dashboard for teacher accounts</p>
            </div>
        </div>
    </div>
@stop

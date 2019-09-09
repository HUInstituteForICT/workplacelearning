@extends('layout.HUerror')
@section('httperrno')
    503
@stop
@section('title')
    {{ __('errors.http.503.title') }}
@stop
@section('content')
    {{ __('errors.http.503.message') }}
@stop

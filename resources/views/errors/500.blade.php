@extends('layout.HUerror')
@section('httperrno')
    500
@stop
@section('title')
    {{ __('errors.http.500.title') }}
@stop
@section('content')
    {{ __('errors.http.500.message') }}
@stop
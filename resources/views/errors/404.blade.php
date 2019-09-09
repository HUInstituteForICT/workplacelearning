@extends('layout.HUerror')
@section('httperrno')
    404
@stop
@section('title')
    {{ __('errors.http.404.title') }}
@stop
@section('content')
    {{ __('errors.http.404.message') }}
@stop
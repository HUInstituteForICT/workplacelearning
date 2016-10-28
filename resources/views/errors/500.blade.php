@extends('layout.HUerror')
@section('httperrno')
    500
@stop
@section('title')
    {{ Lang::get('errors.http.500.title') }}
@stop
@section('content')
    {{ Lang::get('errors.http.500.message') }}
@stop
@extends('layout.HUerror')
@section('httperrno')
    503
@stop
@section('title')
    {{ Lang::get('errors.http.503.title') }}
@stop
@section('content')
    {{ Lang::get('errors.http.503.message') }}
@stop

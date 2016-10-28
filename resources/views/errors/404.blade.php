@extends('layout.HUerror')
@section('httperrno')
    404
@stop
@section('title')
    {{ Lang::get('errors.http.404.title') }}
@stop
@section('content')
    {{ Lang::get('errors.http.404.message') }}
@stop
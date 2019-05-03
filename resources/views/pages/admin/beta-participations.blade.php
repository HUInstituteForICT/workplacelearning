@extends('layout.HUdefault')
@section('title')
    {{ __('misc.beta-participations') }}
@stop
@section('content')

    <div class="container-fluid">

        <div class="row">

            <h1>{{ __('misc.beta-participations') }}</h1>

            <strong>Reflection method beta</strong><br/>
            Total participations: {{ count($participations) }}
            <table class="table">
                <thead>
                <tr>
                    <th>Student</th>
                    <th>Accept date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($participations as $participation)
                    <tr>
                        <td>{{ $participation->student->getInitials() }} {{ $participation->student->lastname }}</td>
                        <td>{{ $participation->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>


    </div>

@stop
@extends('layout.HUdefault')
{{--DEPRECATED VIEW--}}
@section('title')
    {{ __('misc.beta-participations') }}
@stop
@section('content')

    <div class="container-fluid">

        <div class="row">

            <h1>{{ __('misc.beta-participations') }}</h1>

            <strong>Deelnemers die mee willen doen aan het interview</strong><br/>
            Totaal aantal deelnemers {{ count($participations) }}
            <table class="table">
                <thead>
                <tr>
                    <th>Student</th>
                    <th>Mail</th>
                    <th>Opleiding</th>
                    <th>Acceptatie datum</th>
                </tr>
                </thead>
                <tbody>
                @foreach($participations as $participation)
                    <tr>
                        <td>{{ $participation->student->getInitials() }} {{ $participation->student->lastname }}</td>
                        <td>{{ $participation->student->email }}</td>
                        <td>{{ $participation->student->educationProgram->ep_name }}</td>
                        <td>{{ $participation->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>


    </div>

@stop
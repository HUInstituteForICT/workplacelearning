@extends('layout.HUdefault')
@section('title')
    Tips
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <script>
                var operators = {!! json_encode($operators) !!};
                var statisticVariables = {!! json_encode($statisticVariables) !!};
            </script>
            <div class="__reactRoot" id="CreateForm" data-url="{{ route('statistics.store') }}"></div>
        </div>
    </div>
@stop

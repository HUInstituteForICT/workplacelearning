@extends('layout.HUdefault')
@section('title', 'Template Dashboard')
@section('content')
    {{--TODO: lang support--}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{Lang::get('template.dashboard_title')}}</h1>

                @if (count($templates) <= 0)
                    <p>{{Lang::get('template.no_templates_error')}}</p>
                @else
                    @foreach($templates as $template)
                        <a class="list-group-item"
                           href="{{ route('template.show', $template->id) }}">{{ $template->name }}</a>
                    @endforeach
                @endif

                <form action="{{route('template.create')}}" method="get">
                    <button class="btn btn-primary" style="margin-top:10px;" title="CreateTemplate">{{Lang::get('template.create_button')}}
                    </button>
                </form>

            </div>
        </div>
    </div>
@stop

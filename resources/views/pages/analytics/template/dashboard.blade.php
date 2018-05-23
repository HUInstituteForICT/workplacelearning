@extends('layout.HUdefault')
@section('title', 'Template Dashboard')
@section('content')
    <div class="container">

        <h1>{{Lang::get('template.dashboard_title')}}</h1>

        @if (count($templates) <= 0)
            <p>{{Lang::get('template.no_templates_error')}}</p>
        @else
            @foreach($templates as $template)
                <div class="row">
                    <div class="col-sm-11">
                        <a class="list-group-item"
                           href="{{ route('template.show', $template->id) }}">{{ $template->name }}</a>
                    </div>

                    <div class="col-sm-0.5">
                        <form action="{{route('template.destroy', $template->id)}}" method="post" class="frmDelete">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-danger" id="delete" title="DeleteTemplate" style="margin-top: 3px">&times;
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        <form action="{{route('template.create')}}" method="get">
            <button class="btn btn-primary" style="margin-top: 10px"
                    title="CreateTemplate">{{Lang::get('template.create_button')}}
            </button>
        </form>

    </div>

    <script>
        $('.frmDelete').click(function (event) {
            if (!confirm('{{ Lang::get('dashboard.warning') }}')) {
                event.preventDefault();
                return false
            }
        });
    </script>

@stop

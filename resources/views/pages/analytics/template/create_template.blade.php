@extends('layout.HUdefault')
@section('title', 'Template Maken')
@section('content')
    {{--TODO: lang support--}}

    <form action="{{ route('template.index') }}" method="get">
        <button class="btn btn-default" title="Terug"> < Terug
        </button>
    </form>

    <div class="row">
        <div class="col-md-4">
            <h1>Template Maken</h1>
            <form action="" method="post">

                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="query">Query</label>
                    <textarea rows="4" cols="50" maxlength="100" id="query" name="query"
                              class="form-control"></textarea>
                </div>

        {{--        <form action="{{ route('template.destroy', $template) }}" class="frmDelete" method="post" accept-charset="UTF-8">
                    {{ method_field('delete') }}
                    <button class="btn btn-default" style="float: right" title="Opslaan">Opslaan</button>
                </form>--}}



            </form>

        </div>
    </div>

@stop
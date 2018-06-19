@extends('layout.HUdefault')
@section('title', 'Template Dashboard')
@section('content')

    <div class="row">
        <div class="col-md-9">
            <h1>@lang('template.edit_template')</h1>
            <form action="{{ route('template.update', $template->id) }}" class="frmDelete" method="post"
                  accept-charset="UTF-8">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name">@lang('template.name')</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{$template->name}}">
                </div>

                <div class="form-group">
                    <label for="query">Query</label>
                    <textarea rows="4" cols="50" maxlength="100" id="query" name="query"
                              class="form-control">{{$template->query}}</textarea>
                </div>
                <button class="btn btn-primary" style="float: right"
                        title="Opslaan">@lang('template.save')</button>
            </form>

            <form action="{{ route('template.index') }}" method="get">
                <button class="btn btn-default" title="Terug">@lang('template.back')
                </button>
            </form>

        </div>
    </div>

@stop
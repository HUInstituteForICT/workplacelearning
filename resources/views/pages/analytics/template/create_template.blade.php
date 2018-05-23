@extends('layout.HUdefault')
@section('title', 'Template maken')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1>{{Lang::get('template.template_create')}}</h1>

            <form action="{{ route('template.save') }}" method="post">

                <div class="form-group">
                    <label for="name">{{Lang::get('template.name')}}</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="query">Query</label>
                    <textarea rows="4" cols="50" maxlength="100" id="query" name="query"
                              class="form-control"></textarea>
                </div>

                <button class="btn btn-primary" style="float: right" type="submit" title="Opslaan">{{Lang::get('template.save')}}</button>

                {{ csrf_field() }}
            </form>

            <form action="{{ route('template.index') }}" method="get">
                <button class="btn btn-default" title="Terug">{{Lang::get('template.back')}}
                </button>
            </form>

        </div>
    </div>

@stop
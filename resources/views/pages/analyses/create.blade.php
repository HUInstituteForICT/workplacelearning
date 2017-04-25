@extends('layout.HUdefault')
@section('title', 'Analyses - New')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <form action="{{ route('analyses-store') }}" class="form-horizontal" accept-charset="UTF-8"
                      method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                   required="required"
                                   value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cache_duration" class="col-sm-2 control-label">
                            Cache for <i>X</i> seconds
                        </label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="cache_duration" name="cache_duration" placeholder="Cache for X seconds" required="required" value="{{ old('cache_duration') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="query" class="col-sm-2 control-label">Query</label>
                        <div class="col-sm-10">
                            <textarea name="query" id="query" cols="30" rows="10" placeholder="Query"
                                      class="form-control" required="required">{{ old('query') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px"
                                                        height="16px"/> Heb je tips of vragen? Geef ze aan ons door!</a>
            </div>
        </div>
    </div>
@stop
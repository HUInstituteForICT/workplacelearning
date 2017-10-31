@extends('layout.HUdefault')
@section('title', 'Analyses - New')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <form action="{{ route('analytics-store') }}" class="form-horizontal" accept-charset="UTF-8"
                      method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ Lang::get('dashboard.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ Lang::get('dashboard.name') }}"
                                   required="required"
                                   value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cache_duration" class="col-sm-2 control-label">
                            {{ Lang::get('dashboard.cache-for') }} <i>X</i>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="cache_duration" name="cache_duration"
                                   placeholder="{{ Lang::get('dashboard.cache-for') }} X" required="required"
                                   value="{{ old('cache_duration') }}">
                        </div>

                        <div class="col-sm-4">
                            <select class="form-control" name="type_time" id="type_time" required="required"
                                    title="Time type">
                                <option></option>
                                <option value="seconds">{{ Lang::get('dashboard.seconds') }}</option>
                                <option value="minutes">{{ Lang::get('dashboard.minutes') }}</option>
                                <option value="hours">{{ Lang::get('dashboard.hour') }}</option>
                                <option value="days">{{ Lang::get('dashboard.days') }}</option>
                                <option value="weeks">{{ Lang::get('dashboard.weeks') }}</option>
                                <option value="months">{{ Lang::get('dashboard.months') }}</option>
                                <option value="years">{{ Lang::get('dashboard.years') }}</option>
                            </select>
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
                            <button type="submit" class="btn btn-primary">{{ Lang::get('dashboard.add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@stop
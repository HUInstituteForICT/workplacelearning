@extends('layout.HUdefault')
@section('title', 'Analyses - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('analyses.title') }}: {{ $analysis->name }}</h1>
                <h4>{{ Lang::get('dashboard.cache-note') }}</h4>
                <form action="{{ route('analytics-update', $analysis->id) }}" class="form-horizontal" accept-charset="UTF-8"
                      method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ Lang::get('dashboard.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                   required="required"
                                   value="{{ old('name', $analysis->name) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cache_duration" class="col-sm-2 control-label">
                            {{ Lang::get('dashboard.cache-for') }} <i>X</i>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="cache_duration" name="cache_duration"
                                   placeholder="{{ Lang::get('dashboard.cache-for') }} X" required="required"
                                   value="{{ old('cache_duration', $analysis->cache_duration) }}">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" name="type_time" id="type_time" required="required"
                                    title="Time type">
                                <option value="seconds" <?= ($analysis->type_time === 'seconds') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.seconds') }}</option>
                                <option value="minutes" <?= ($analysis->type_time === 'minutes') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.minutes') }}</option>
                                <option value="hours" <?= ($analysis->type_time === 'hours') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.hour') }}</option>
                                <option value="days" <?= ($analysis->type_time === 'days') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.days') }}</option>
                                <option value="weeks" <?= ($analysis->type_time === 'weeks') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.weeks') }}</option>
                                <option value="months" <?= ($analysis->type_time === 'months') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.months') }}</option>
                                <option value="years" <?= ($analysis->type_time === 'years') ? 'selected' : ''; ?>>{{ Lang::get('dashboard.years') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="query" class="col-sm-2 control-label">Query</label>
                        <div class="col-sm-10">
                            <textarea name="query" id="query" cols="30" rows="10" placeholder="Query"
                                      class="form-control" required="required">{{ old('query', $analysis->query) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@stop
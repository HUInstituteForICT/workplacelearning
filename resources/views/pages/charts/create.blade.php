@extends('layout.HUdefault')
@section('title', Lang::get('charts.title') . ' - New')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('charts.title') }}</h1>
                <form action="{{ route('charts.create_step_2') }}" class="form-horizontal" accept-charset="UTF-8"
                      method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ Lang::get('dashboard.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                   required="required"
                                   value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="analysis_id" class="col-sm-2 control-label">{{ Lang::get('dashboard.analysis') }}</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="analysis_id" id="analysis_id" required="required">
                                <option></option>
                                @foreach($analyses as $analysis)
                                    <option value="{{ $analysis->id }}">{{ $analysis->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-2 control-label">{{ Lang::get('dashboard.type') }}</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="type_id" name="type_id" required="required">
                                <option></option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">{{ Lang::get('general.create') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
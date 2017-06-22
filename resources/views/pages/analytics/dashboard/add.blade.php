@extends('layout.HUdefault')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>Dashboard</h1>
                <form action="{{ route('dashboard.save') }}" method="post" accept-charset="UTF-8"
                      class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="position" class="col-sm-2 control-label">Position</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="position" name="position"
                                   placeholder="Position"
                                   required="required"
                                   min="0"
                                   value="{{ old('position') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="chart_id" class="col-sm-2 control-label">Graph</label>

                        <div class="col-sm-10">
                            <select class="form-control"
                                    name="chart_id"
                                    id="chart_id"
                                    title="Graph"
                                    required="required">
                                <option></option>
                                @foreach($analyses as $analysis)
                                    <optgroup label="{{ $analysis->name }}">
                                        @foreach($analysis->charts as $chart)
                                            <option value="{{ $chart->id }}">{{ $chart->label }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
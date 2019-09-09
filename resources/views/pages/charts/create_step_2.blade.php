@extends('layout.HUdefault')
@section('title', __('charts.title') . ' - New')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ __('charts.title') }}</h1>
                <form action="{{ route('charts.store') }}" class="form-horizontal" accept-charset="UTF-8"
                      method="post">
                    {{ csrf_field() }}
                    @if(count((array)$analysis->data['data']) > 0)
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{ __('dashboard.name') }}</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $name }}</p>
                            <input type="hidden" name="name" value="{{ $name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="analysis_id" class="col-sm-2 control-label">{{ __('dashboard.analysis') }}</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $analysis->name }}</p>
                            <input type="hidden" name="analysis_id" value="{{ $analysis->id }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-2 control-label">{{ __('dashboard.type') }}</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $type->name }}</p>
                            <input type="hidden" name="type_id" value="{{ $type->id }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="label" class="col-sm-2 control-label">{{ __('general.label') }}</label>
                        <div class="col-sm-10">
                            <input type="text" name="label" id="label" class="form-control" required="required" placeholder="{{ __('general.label') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="x_axis" class="col-sm-2 control-label">X {{ __('dashboard.axis') }}</label>
                        <div class="col-sm-10">
                            <select name="x_axis" id="x_axis" class="form-control" required="required">
                                <option></option>
                                @foreach(array_keys((array) $analysis->data['data'][0]) as $key)
                                    <option value="{{ $key }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="y_axis" class="col-sm-2 control-label">Y {{ __('dashboard.axis') }}</label>
                        <div class="col-sm-10">
                            <select name="y_axis" id="y_axis" class="form-control" required="required">
                                <option></option>
                                @foreach(array_keys((array) $analysis->data['data'][0]) as $key)
                                    <option value="{{ $key }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">{{ __('general.create') }}</button>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ __('dashboard.error') }}</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ __('dashboard.error-create-no-data') }} <a href="{{ route('analytics-show', $analysis->id) }}">{{ __('dashboard.query-change') }}</a> {{ __('general.or') }} <a href="javascript:void(0)" onclick="window.history.go(-1)">{{ __('general.go-back') }}</a>.</p>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script>
      $(document).on('ready', function () {
        $('#x_axis').on('change', function () {
          disableOther($(this), $('#y_axis'))
        });
        $('#y_axis').on('change', function () {
          disableOther($(this), $('#x_axis'))
        });
        var disableOther = function (me, other) {
//          me.find('option').removeAttr('disabled')
          var items = other.find('option');
          items.removeAttr('disabled');
          items.eq([me.prop('selectedIndex')]).attr('disabled', 'disabled')
        }
      })
    </script>
@stop
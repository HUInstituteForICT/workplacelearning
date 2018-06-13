<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('querybuilder.step4.title')</h4>
</div>
<div class="modal-body" style="height: 450px">

    <form id="wizard-form">
        <label for="name">@lang('querybuilder.step4.name'):</label><br>
        <div class="form-group row">
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name="name" placeholder="@lang('querybuilder.step4.name-caption')"
                       required="required"
                       value="{{ isset($data['name']) ? $data['name'] : "" }}">
            </div>
        </div>

        <label for="type_id">@lang('querybuilder.step4.cache'):</label><br>
        <div class="form-group row">
            <div class="col-sm-2">
                <input type="number" class="form-control" id="cache_duration" name="cache_duration" placeholder="@lang('querybuilder.step4.cache-caption')"
                       required="required" value="{{ isset($data['cache_duration']) ? $data['cache_duration'] : "" }}">
            </div>
            <div class="col-sm-3">
                <select class="form-control" name="type_time" id="type_time" required="required" title="Time type">
                    <option></option>
                    <option value="seconds" {{ isset($data['type_time']) && $data['type_time'] == "seconds" ? "checked" : "" }}>@lang('querybuilder.step4.seconds')</option>
                    <option value="minutes" {{ isset($data['type_time']) && $data['type_time'] == "minutes" ? "checked" : "" }}>@lang('querybuilder.step4.minutes')</option>
                    <option value="hours" {{ isset($data['type_time']) && $data['type_time'] == "hours" ? "checked" : "" }}>@lang('querybuilder.step4.hours')</option>
                    <option value="days" {{ isset($data['type_time']) && $data['type_time'] == "days" ? "checked" : "" }}>@lang('querybuilder.step4.days')</option>
                    <option value="weeks" {{ isset($data['type_time']) && $data['type_time'] == "weeks" ? "checked" : "" }}>@lang('querybuilder.step4.weeks')</option>
                    <option value="months" {{ isset($data['type_time']) && $data['type_time'] == "months" ? "checked" : "" }}>@lang('querybuilder.step4.months')</option>
                    <option value="years" {{ isset($data['type_time']) && $data['type_time'] == "years" ? "checked" : "" }}>@lang('querybuilder.step4.years')</option>
                </select>
            </div>
        </div>

        <label for="type_id">@lang('querybuilder.step4.graph-type'):</label><br>
        <div class="form-group row">
            <div class="col-md-3">
                <select class="form-control" id="type_id" name="type_id" required="required">
                    <option></option>
                    @foreach($chartTypes as $type)
                        <option value="{{ $type->id }}" {{ isset($data['type_id']) && $data['type_id'] == $type->id ? "checked" : "" }}>@lang('querybuilder.step4.'.$type->slug)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="type_id">@lang('querybuilder.step4.x-axis'):</label><br>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <select class="form-control" id="x_axis" name="x_axis"
                                required="required" value="{{ isset($data['x_axis']) ? $data['x_axis'] : $labels[0] }}">
                            @foreach($labels as $label)
                                <option value="{{ $label }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label for="type_id">@lang('querybuilder.step4.y-axis'):</label><br>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <select class="form-control" id="y_axis" name="y_axis"
                               required="required" value="{{ isset($data['y_axis']) ? $data['y_axis'] : $labels[1] }}">
                            @foreach($labels as $label)
                                <option value="{{ $label }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        <div class="col-sm-5">
            <div class="chart-container" style="width: 300px; height: 200px;"></div>
        </div>
        </div>
    </form>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(3);">@lang('querybuilder.previous')</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(5);">@lang('querybuilder.save')</button>
</div>


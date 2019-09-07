<div class="modal-header">
    <h4 class="modal-title">@lang('querybuilder.step3.title')</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="analysis_entity">@lang('querybuilder.step3.data')</label>
        @for($i=0; $i<2; $i++)
        <div class="form-group row query-data-container">
            <div class="col-md-3">
                <select class="form-control query-data-table" name="query_data[{{ $i }}][table]">
                    <option {{ isset($data['query_data'][$i]['table']) ? ($data['query_data'][$i]['table'] == $data['analysis_entity'] ? 'selected' : '') : '' }} value="{{ $data['analysis_entity'] }}">@lang('querybuilder.'.$data['analysis_entity'])</option>
                    @if(isset($relations))
                    @foreach($relations as $r)
                        <option {{ isset($data['query_data'][$i]['table']) ? ($data['query_data'][$i]['table'] == $r ? 'selected' : '') : '' }} value="{{ $r }}">{{ __('querybuilder.'.$r) }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_data[{{ $i }}][column]">
                        @foreach($columns[isset($data['query_data']) ? $data['query_data'][$i]['table'] : $data['analysis_entity']] as $c)
                            <option {{ isset($data['query_data'][$i]['column']) ? ($data['query_data'][$i]['column'] == $c ? 'selected' : '') : '' }} value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][type]">
                    <option value="data" {{ isset($data['query_data'][$i]['type']) ? ($data['query_data'][$i]['type'] == 'data' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-data')</option>
                    <option value="sum" {{ isset($data['query_data'][$i]['type']) ? ($data['query_data'][$i]['type'] == 'sum' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-sum')</option>
                    <option value="count" {{ isset($data['query_data'][$i]['type']) ? ($data['query_data'][$i]['type'] == 'count' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-count')</option>
                    <option value="avg" {{ isset($data['query_data'][$i]['type']) ? ($data['query_data'][$i]['type'] == 'avg' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-avg')</option>
                </select>
            </div>
        </div>
        @endfor
        <!--a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a-->
        <label for="analysis_entity">@lang('querybuilder.step3.filters')</label>
        <div class="query-filter-container">
            @if(isset($data['query_filter']))
            @for($i = 0; $i < count($data['query_filter']); $i++)
            <div class="form-group row" data-id="{{ $i }}">
                <div class="col-md-1" style="width: 25px;"><a href="#" class="query-delete-filter" style="line-height: 34px; text-decoration: none;">X</a></div>
                <div class="col-md-3">
                    <select class="form-control query-data-table" name="query_filter[{{ $i }}][table]">
                        <option value="{{ $data['analysis_entity'] }}" {{ ($data['query_filter'][$i]['table'] == $data['analysis_entity']) ? 'selected' : '' }}>
                            @lang('querybuilder.'.$data['analysis_entity'])
                        </option>

                        @if(isset($relations))
                        @foreach($relations as $r)
                            <option value="{{ $r }}" {{ ($data['query_filter'][$i]['table'] == $r) ? 'selected' : '' }}>
                                {{ __('querybuilder.'.$r) }}
                            </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_filter[{{ $i }}][column]">
                    @foreach($columns[$data['query_filter'][$i]['table']] as $c)
                        <option value="{{ $c }}" {{ ($data['query_filter'][$i]['column'] == $c) ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-filter-type" name="query_filter[{{ $i }}][type]">
                        <option value="equals" {{ ($data['query_filter'][$i]['type'] == 'equals') ? 'selected' : '' }}>@lang('querybuilder.step3.filter-equals')</option>
                        <option value="largerthan" {{ ($data['query_filter'][$i]['type'] == 'largerthan') ? 'selected' : '' }}>@lang('querybuilder.step3.filter-largerthan')</option>
                        <option value="smallerthan" {{ ($data['query_filter'][$i]['type'] == 'smallerthan') ? 'selected' : '' }}>@lang('querybuilder.step3.filter-smallerthan')</option>
                        <option value="group" {{ ($data['query_filter'][$i]['type'] == 'group') ? 'selected' : '' }}>@lang('querybuilder.step3.filter-groupby')</option>
                    </select>
                </div>
                <div class="col-md-2" style="width: 12%;">
                    <input name="query_filter[{{ $i }}][value]" style="{{ $data['query_filter'][$i]['type'] == 'group' ? 'display: none;': '' }}"
                           class="form-control query-filter-value" placeholder="@lang('querybuilder.step3.value')"
                            value="{{ $data['query_filter'][$i]['value'] }}">
                </div>

            </div>
            @endfor
            @endif
        </div>
        <a style="font-size: 20px; text-decoration: none; display: block;" class="query-add-filter" href="#">+</a>

        <label>@lang('querybuilder.step3.sort')</label>
        @for($i=0; $i<1; $i++)
        <div class="form-group row query-data-container">
            <div class="col-md-3">
                <select class="form-control query-data-table" name="query_sort[{{ $i }}][table]">
                    <option {{ isset($data['query_sort'][$i]['table']) ? ($data['query_sort'][$i]['table'] == $data['analysis_entity'] ? 'selected' : '') : '' }}
                            value="{{ $data['analysis_entity'] }}">@lang('querybuilder.'.$data['analysis_entity'])</option>
                    @if(isset($relations))
                    @foreach($relations as $r)
                        <option {{ isset($data['query_sort'][$i]['table']) ? ($data['query_sort'][$i]['table'] == $r ? 'selected' : '') : '' }}
                                value="{{ $r }}">{{ __('querybuilder.'.$r) }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control query-data-column" name="query_sort[{{ $i }}][column]">
                    @foreach($columns[isset($data['query_sort']) ? $data['query_sort'][$i]['table'] : $data['analysis_entity']] as $c)
                        <option {{ isset($data['query_sort'][$i]['column']) ? ($data['query_sort'][$i]['column'] == $c ? 'selected' : '') : '' }}
                                value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_sort[{{ $i }}][type]">
                    <option value="data" {{ isset($data['query_sort'][$i]['type']) ? ($data['query_sort'][$i]['type'] == 'data' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-data')</option>
                    <option value="sum" {{ isset($data['query_sort'][$i]['type']) ? ($data['query_sort'][$i]['type'] == 'sum' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-sum')</option>
                    <option value="count" {{ isset($data['query_sort'][$i]['type']) ? ($data['query_sort'][$i]['type'] == 'count' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-count')</option>
                    <option value="avg" {{ isset($data['query_sort'][$i]['type']) ? ($data['query_sort'][$i]['type'] == 'avg' ? 'selected' : '') : ''}}>@lang('querybuilder.step3.action-avg')</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_sort[{{ $i }}][order]">
                    <option value="asc" {{ isset($data['query_sort'][$i]['order']) ? ($data['query_sort'][$i]['order'] == 'asc' ? 'selected' : '') : ''}}>
                        @lang('querybuilder.step3.asc')
                    </option>
                    <option value="desc" {{ isset($data['query_sort'][$i]['order']) ? ($data['query_sort'][$i]['order'] == 'desc' ? 'selected' : '') : ''}}>
                        @lang('querybuilder.step3.desc')
                    </option>
                </select>
            </div>
        </div>
        @endfor

        <label>@lang('querybuilder.step3.limit')</label>
        <div class="form-group row query-limit-container">
            <div class="col-md-3">
                <input type="number" name="query_limit" class="form-control query-filter-value"
                       placeholder="@lang('querybuilder.step3.limit-caption')" value="{{ isset($data['query_limit']) ? $data['query_limit'] : '' }}">
            </div>
        </div>
    </form>
    <div style="
    position: absolute;
    right:  0;
    top:  0;
    bottom: 0;
    width: 25%;
    border-left: 1px solid #ddd;
    background: #fff;">
        <div id="query-result"></div>
        <button type="button" class="btn btn-primary" style="position: fixed; bottom: 66px; width: 223px; border-radius: 0;"
                onclick="Wizard.executeBuilderQuery();">Test query</button>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">@lang('querybuilder.previous')</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">@lang('querybuilder.next')</button>
</div>
<div class="modal-header">
    <h4 class="modal-title">@lang('querybuilder.step3.title')</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="analysis_entity">@lang('querybuilder.step3.data')</label>
        @for($i=0; $i<2; $i++)
        <div class="form-group row query-data-container">
            <!--div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div-->
            <div class="col-md-3">
                <select class="form-control query-data-table" name="query_data[{{ $i }}][table]">
                    <option value="{{ $data['analysis_entity'] }}">@lang('querybuilder.'.$data['analysis_entity'])</option>
                    @if(isset($data['analysis_relation']))
                    @foreach($data['analysis_relation'] as $r)
                        <option value="{{ $r }}">{{ Lang::get('querybuilder.'.$r) }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control query-data-column" name="query_data[{{ $i }}][column]"></select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][type]">
                    <option value="data">@lang('querybuilder.step3.action-data')</option>
                    <option value="sum">@lang('querybuilder.step3.action-sum')</option>
                    <option value="count">@lang('querybuilder.step3.action-count')</option>
                </select>
            </div>
        </div>
        @endfor
        <!--a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a-->
        <label for="analysis_entity">Filters</label>
        <div class="query-filter-container">
            @if(isset($data['query_filter']))
            @foreach($data['query_filter'] as $filter)
            <div class="form-group row" data-id="1">
                <div class="col-md-1" style="width: 25px;"><a href="#" class="query-delete-filter" style="line-height: 34px; text-decoration: none;">X</a></div>
                <div class="col-md-3">
                    <select class="form-control query-data-table" name="query_filter[1][table]">
                        <option value="{{ $data['analysis_entity'] }}">@lang('querybuilder.'.$data['analysis_entity'])</option>
                        @if(isset($data['analysis_relation']))
                        @foreach($data['analysis_relation'] as $r)
                            <option value="{{ $r }}">{{ Lang::get('querybuilder.'.$r) }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_filter[1][column]"></select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-filter-type" name="query_filter[1][type]">
                        <option value="equals" selected>@lang('querybuilder.step3.filter-equals')</option>
                        <option value="between">@lang('querybuilder.step3.filter-between')</option>
                        <option value="largerthan">@lang('querybuilder.step3.filter-largerthan')</option>
                        <option value="smallerthan">@lang('querybuilder.step3.filter-smallerthan')</option>
                        <option value="group">@lang('querybuilder.step3.filter-groupby')</option>
                        <option value="limit">@lang('querybuilder.step3.filter-limit')</option>
                    </select>
                </div>
                <div class="col-md-2" style="width: 12%;">
                    <input name="query_filter[1][value]" class="form-control query-filter-value" placeholder="@lang('querybuilder.step3.value')">
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <a style="font-size: 20px; text-decoration: none; display: block;" class="query-add-filter" href="#">+</a>
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
        <button type="button" class="btn btn-primary" onclick="Wizard.executeBuilderQuery();">Test query</button>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">@lang('querybuilder.previous')</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">@lang('querybuilder.next')</button>
</div>
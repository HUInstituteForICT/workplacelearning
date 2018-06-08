<div class="modal-header">
    <h4 class="modal-title">Stap 3: filters, sortering en groepering</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="analysis_entity">Gegevens</label>
        @for($i=0; $i<2; $i++)
        <div class="form-group row query-data-container">
            <!--div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div-->
            <div class="col-md-2">
                <select class="form-control query-data-table" name="query_data[{{ $i }}][table]">
                    <option value="{{ $data['analysis_entity'] }}">{{ $data['analysis_entity'] }}</option>
                    @if(isset($data['analysis_relation']))
                    @foreach($data['analysis_relation'] as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control query-data-column" name="query_data[{{ $i }}][column]"></select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][type]">
                    <option value="data">Data</option>
                    <option value="sum">Sum</option>
                    <option value="count">Count</option>
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
                <div class="col-md-2">
                    <select class="form-control query-data-table" name="query_filter[1][table]">
                        <option value="{{ $data['analysis_entity'] }}">{{ $data['analysis_entity'] }}</option>
                        @if(isset($data['analysis_relation']))
                        @foreach($data['analysis_relation'] as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_filter[1][column]"></select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-type" name="query_filter[1][type]">
                        <option value="table">Table Filter</option>
                        <option value="equals" selected>Equals</option>
                        <option value="between">Between</option>
                        <option value="largerthan">Larger than</option>
                        <option value="smallerthan">Smaller than</option>
                        <option value="group">Group by</option>
                        <option value="limit">Limit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <!--select class="form-control" name="query_data[]" id="analysis_entity">
                        <option>Value</option>
                    </select-->
                    <input name="query_filter[1][value]" class="form-control" placeholder="Value">
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
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>
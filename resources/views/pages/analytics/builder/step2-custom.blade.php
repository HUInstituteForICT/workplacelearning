<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('querybuilder.step2.custom')</h4>
</div>
<div class="modal-body" style="height: 450px">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <form id="wizard-form">
                <div class="form-group">
                    <label for="Query">{{__('custom_query.query')}}</label>
                    <textarea rows="4" cols="50" maxlength="1000" id="customQuery" name="customQuery"
                              class="form-control">{{ (isset($data['customQuery'])) ? $data['customQuery'] : '' }}</textarea>
                </div>

                <button class="btn btn-primary" style="float: right"
                        title="testQuery" id="testQuery">Test Query</button>

                <div class="form-group">
                    <label for="dataFromQuery">{{__('custom_query.dataQuery')}}</label>
                    <div id="dataCustomQuery"></div>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" onclick="Wizard.step(1);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>
<script>
    $("#testQuery").click(function(){
        var query = $('#customQuery').val();
        $uitkomst = Wizard.executeCustomBuilderQuery(query);

        return false;
    });
</script>
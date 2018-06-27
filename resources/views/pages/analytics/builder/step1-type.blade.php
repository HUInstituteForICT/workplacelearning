<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('querybuilder.step1.title')</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <p>@lang('querybuilder.step1.caption')</p>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_build" value="build" {{ isset($data['analysis_type']) ? $data['analysis_type'] == "build" ? "checked" : "" : "checked" }}>
            <label class="form-check-label" for="analysis_type_build">
                @lang('querybuilder.step1.builder')
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_template" value="template" {{ isset($data['analysis_type']) && $data['analysis_type'] == "template" ? "checked" : "" }}>
            <label class="form-check-label" for="analysis_type_template">
                @lang('querybuilder.step1.template')
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_custom" value="custom" {{ isset($data['analysis_type']) && $data['analysis_type'] == "custom" ? "checked" : "" }}>
            <label class="form-check-label" for="analysis_type_custom">
                @lang('querybuilder.step1.custom')
            </label>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" data-dismiss="modal">@lang('querybuilder.cancel')</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(2);">@lang('querybuilder.next')</button>
</div>
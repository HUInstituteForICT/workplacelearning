<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Stap 1: Soort analyse</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <p>Wat voor soort analyse wil je toevoegen?</p>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_build" value="build" {{ isset($data['analysis_type']) ? $data['analysis_type'] == "build" ? "checked" : "" : "checked" }}>
            <label class="form-check-label" for="analysis_type_build">
                Query bouwen
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_template" value="template" {{ isset($data['analysis_type']) && $data['analysis_type'] == "template" ? "checked" : "" }}>
            <label class="form-check-label" for="analysis_type_template">
                Analyse op basis van template
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="analysis_type" id="analysis_type_custom" value="custom" {{ isset($data['analysis_type']) && $data['analysis_type'] == "custom" ? "checked" : "" }}>
            <label class="form-check-label" for="analysis_type_custom">
                Eigen SQL Query
            </label>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" data-dismiss="modal">Annuleren</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(2);">Volgende</button>
</div>
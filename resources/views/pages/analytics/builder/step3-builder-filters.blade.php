<div class="modal-header">
    <h4 class="modal-title">Stap 3: filters, sortering en groepering</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <div class="form-group">
            <label for="analysis_entity">Gegevens</label>
            <select class="form-inline" name="query_data[]" id="analysis_entity">
                <option>Column</option>
                <option>Sum</option>
                <option>Count</option>
            </select>
            <select class="form-inline" name="query_data[]" id="analysis_entity">
                <option>Column</option>
                <option>Sum</option>
                <option>Count</option>
            </select>
        </div>
        <div class="form-group">
            <label for="analysis_entity">Filters</label>
            <select class="form-control" name="analysis_entity" id="analysis_entity">
                <option>Column</option>
                <option>Sum</option>
                <option>Count</option>
            </select>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>
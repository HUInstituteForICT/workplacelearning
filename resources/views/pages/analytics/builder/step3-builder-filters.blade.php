<div class="modal-header">
    <h4 class="modal-title">Stap 3: filters, sortering en groepering</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="analysis_entity">Gegevens</label>
        <div class="form-group row">
            <div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Column</option>
                    <option>Sum</option>
                    <option>Count</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Table</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Column</option>
                </select>
            </div>
        </div>
        <a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a>
        <label for="analysis_entity">Filters</label>
        <div class="form-group row">
            <div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Table Filter</option>
                    <option>Between</option>
                    <option>Equals</option>
                    <option>Larger than</option>
                    <option>Smaller than</option>
                    <option>Group by</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Table</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Column</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Value</option>
                </select>
            </div>
        </div>
        <a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>
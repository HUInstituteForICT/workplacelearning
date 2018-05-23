<div class="modal-header">
    <h4 class="modal-title">Stap 2: Kies entiteit en relaties</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form>
        <div class="form-group">
            <label for="analysis_entity">Entiteit</label>
            <select class="form-control" name="analysis_entity" id="analysis_entity">
                <option>Student</option>
                <option>Accesslog</option>
            </select>
        </div>
        <p style="font-weight: bold;">Relaties</p>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_1" value="custom">
            <label class="form-check-label" for="analysis_relations_1">
                Test relatie
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="analysis_relations[]" id="analysis_relations_2" value="custom">
            <label class="form-check-label" for="analysis_relations_2">
                Test relatie 2
            </label>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/1');})();">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/3');})();">Volgende</button>

</div>
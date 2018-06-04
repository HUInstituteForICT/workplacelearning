<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Stap 4: Grafiek maken</h4>
</div>
<div class="modal-body" style="height: 450px">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Naam:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" placeholder="Naam analyse"
                   required="required"
                   value="{{ old('name') }}">
        </div>
    </div>

    <div class="form-group">
        Query uit vorige stap

    </div>

    <div class="form-group">
        <label for="type_id" class="col-sm-6 control-label">Cache voor:</label>
        <div class="col-sm-10">
        <input type="number" class="form-control" id="cache_duration" name="cache_duration" placeholder="Cache voor X" required="required" value="">
        </div>
        <select class="form-control" name="type_time" id="type_time" required="required" title="Time type">
            <option></option>
            <option value="seconds">Seconden</option>
            <option value="minutes">Minuten</option>
            <option value="hours">Uren</option>
            <option value="days">Dagen</option>
            <option value="weeks">Weken</option>
            <option value="months">Maanden</option>
            <option value="years">Jaren</option>
        </select>
    </div>

    <div class="form-group">
        <label for="type_id" class="col-sm-2 control-label">Kies een grafiek:</label>
        <div class="col-sm-10">
            <select class="form-control" id="type_id" name="type_id" required="required">
                <option></option>
                <option>Pie chart</option>
                <option>Bar chart</option>
                <option>Line chart</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/3');})();">Vorige</button>
    <button type="button" class="btn btn-primary">Toevoegen</button>
</div>


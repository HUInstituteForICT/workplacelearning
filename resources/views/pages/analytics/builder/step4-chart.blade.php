<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Stap 4: Grafiek maken</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="name">Naam:</label><br>
        <div class="form-group row">
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" name="name" placeholder="Naam analyse"
                       required="required"
                       value="{{ old('name') }}">
            </div>
        </div>

        <label for="type_id">Cache voor:</label><br>
        <div class="form-group row">
            <div class="col-sm-3">
                <input type="number" class="form-control" id="cache_duration" name="cache_duration" placeholder="Cache voor X" required="required" value="">
            </div>
            <div class="col-sm-2">
                <select class="form-control" name="type_time" id="type_time" required="required" title="Time type">
                    <option></option>
                    <option value="seconds">Seconden</option>
                    <option value="minutes" selected>Minuten</option>
                    <option value="hours">Uren</option>
                    <option value="days">Dagen</option>
                    <option value="weeks">Weken</option>
                    <option value="months">Maanden</option>
                    <option value="years">Jaren</option>
                </select>
            </div>
        </div>

        <label for="type_id">Kies een grafiek:</label><br>
        <div class="form-group row">
            <div class="col-md-3">
                <select class="form-control" id="type_id" name="type_id" required="required">
                    <option></option>
                    <option>Pie chart</option>
                    <option>Bar chart</option>
                    <option>Line chart</option>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(3);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(5);">Toevoegen</button>
</div>


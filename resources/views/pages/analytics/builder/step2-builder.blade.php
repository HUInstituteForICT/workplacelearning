<div class="modal-header">
    <h4 class="modal-title">Stap 2: Kies entiteit en relaties</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form>
        <div class="form-group">
            <label for="analysis_entity">Entiteit</label>
            <select class="form-control" name="analysis_entity" id="analysis_entity">
                @foreach($models as $model => $class)
                    <option>{{ $model }}</option>
                @endforeach
            </select>
        </div>
        <p style="font-weight: bold;">Relaties</p>
        <div class="relations">
        @foreach($relations as $relation => $type)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_1" value="custom">
                <label class="form-check-label" for="analysis_relations_1">
                    {{ $relation }}
                </label>
            </div>
        @endforeach
        </div>
    </form>
    <script>

        $(document).ready(function() {
            $('#analysis_entity').on('change', function(data) {
                $.getJSON( "https://localhost/dashboard/builder/relations/" + $(this).val(), function( data ) {
                    var items = "";
                    $.each( data, function( key, val ) {
                        items += ` <div class="form-check">
                <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_1" value="custom">
                <label class="form-check-label" for="analysis_relations_1">
                    ${key}
                            </label>
                        </div>`;
                    });

                    $('.relations').html(items);
                });
            });
        });
    </script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/1');})();">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/3');})();">Volgende</button>

</div>
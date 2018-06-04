<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Stap 2: Voer query in</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form action="{{ route('analytics-store') }}" class="form-horizontal" accept-charset="UTF-8"
    method="post">
        <div class="form-group">
            <label for="query">Query</label>
            <textarea rows="4" cols="50" maxlength="1000" id="customQuery" name="customQuery"
                      class="form-control" required="required"></textarea>
        </div>

        <div class="col-md-6">
            <button type="button" style="float:right" class="btn"  onclick="">Voer query uit</button>
        </div>

        <div class="form-group">
            <label for="query">Resultaat</label>
            <textarea rows="4" cols="50" maxlength="1000" id="query" name="customQueryResult"
                      class="form-control"></textarea>
        </div>
    </form>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/1');})();">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/4');})();">Volgende</button>
</div>
<script>
    // Real time query result in veld Resultaat
</script>
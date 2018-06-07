<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Stap 2: Voer query in</h4>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h1>{{Lang::get('custom_query.custom_query')}}</h1>

            <form action="{{ route('query.save') }}" method="post" id="saveForm">

                <div class="form-group">
                    <label for="Query">{{Lang::get('custom_query.query')}}</label>
                    <textarea rows="4" cols="50" maxlength="1000" id="customQuery" name="customQuery"
                              class="form-control"></textarea>
                </div>

                <button class="btn btn-primary" style="float: right" type="submit"
                        title="testQuery">{{Lang::get('custom_query.execute')}}</button>

                <div class="form-group">
                    <label for="dataFromQuery">{{Lang::get('custom_query.dataQuery')}}</label>
                    <textarea rows="10" cols="50" maxlength="5000" id="dataCustomQuery" name="dataCustomQuery"
                              class="form-control"></textarea>
                </div>

                <!--https://stackoverflow.com/questions/5207160/what-is-a-csrf-token-what-is-its-importance-and-how-does-it-work/33829607#33829607-->
                {{ csrf_field() }}
            </form>

            </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/1');})();">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="(function() { $('#QueryBuilder').load('/dashboard/builder/step/4');})();">Volgende</button>
</div>
<script>
    // Real time query result in veld Resultaat
    // UITZOEKEN HOE IK DIT DOE
</script>
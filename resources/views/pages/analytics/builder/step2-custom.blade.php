<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">@lang('querybuilder.step2.custom')</h4>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h1>{{Lang::get('custom_query.custom_query')}}</h1>

            <form id="wizard-form">

                <div class="form-group">
                    <label for="Query">{{Lang::get('custom_query.query')}}</label>
                    <textarea rows="4" cols="50" maxlength="1000" id="customQuery" name="customQuery"
                              class="form-control"></textarea>
                </div>

                <button class="btn btn-primary" style="float: right" type=""
                        title="testQuery" id="testQuery">Test Query</button>

                <div class="form-group">
                    <label for="dataFromQuery">{{Lang::get('custom_query.dataQuery')}}</label>
                    <textarea readonly rows="10" cols="50" maxlength="5000" id="dataCustomQuery" name="dataCustomQuery"
                              class="form-control"></textarea>
                </div>

                <!--https://stackoverflow.com/questions/5207160/what-is-a-csrf-token-what-is-its-importance-and-how-does-it-work/33829607#33829607-->
                {{ csrf_field() }}
            </form>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>
<script>
    // Real time query result in veld Resultaat

    //$analyse = new Analysis();
    //$analyse=>query = $("#customQuery").val();
    //$analyse=>save();

    $("#testQuery").click(function(){
        var $query = $('#customQuery').val();

        // Voer hier analyse uit op database, i.p.v. statisch antwoord
        var $DataResultQuery = $query;

        window.alert("query = " + $query + " query 2 = " + $DataResultQuery + "");
        $('#dataCustomQuery').val($DataResultQuery);
    });
</script>
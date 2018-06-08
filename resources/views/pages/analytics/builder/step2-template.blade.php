<div class="modal-header">
    <h4 class="modal-title">Stap 2: Kies en vul een template in</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="chosen_template">Template</label>
                        <select class="form-control template-select" name="chosen_template" id="chosen_template">
                            @foreach($templates as $template)
                                <option name="{{$loop->index}}" {{ isset($data['chosen_template']) && $data['chosen_template'] == $template ? "selected" : "" }}>{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p style="font-weight: bold;">Query</p>
                    <div class="query-div">
                        <textarea rows="4" cols=100 maxlength="1000" id="query" name="query"
                                  class="form-control query-area"></textarea>
                    </div>

                    <div id="paramGroup" style="margin-top: 15px">
                        {{--JS will load parameters here--}}
                    </div>

                </div>
            </div>


        </div>
    </form>
    <script>
        let templates = JSON.parse('{!! json_encode($templates) !!}');
        let tableNames = JSON.parse('{!! json_encode($tableNames) !!}');
        let columnNames = JSON.parse('{!! json_encode($columnNames) !!}');
        //TODO support tableName as parameter

        $('.template-select').on("change", function () {
            let optionIndex = $(this).find('option:selected').attr("name");
            $('.query-area').val(templates[optionIndex]['query']);

            let templateID = templates[optionIndex]['id'];
            $.getJSON("{{ route('template.parameters') }}/" + templateID, function (data) {
                $('#paramGroup').empty();

                for (let i = 0; i < data.length; i++) {
                    let param = data[i];
                    let paramName = param['name'];
                    let paramType = param['type_name'].toString().toLowerCase();
                    let tableName = param['table'];
                    let columnName = param['column'];

                    let field = "";

                    if (paramType === 'column value') {

                        $.getJSON("{{ route('column-values') }}/" + tableName + "/" + columnName, function (data) {
                            field += `<select class="form-control table-select">`;

                            $.each(data, function (key, val) {
                                if (val != null) {
                                    val = val[Object.keys(val)[0]];
                                    field += "<option id='" + val + "'>" + val + "</option>";
                                }
                            });

                            field += `</select>`;
                            addParamRow(i, paramName, field);
                        });

                    } else {
                        if (paramType === 'column') {
                            let colNames = columnNames[tableName];
                            if (colNames != null) {
                                field += `<select class="form-control table-select">`;

                                for (let j = 0; j < colNames.length; j++) {
                                    let colName = colNames[j];
                                    field += "<option id='" + colName + "'>" + colName + "</option>";
                                }
                                field += `</select>`;
                            }
                        } else {
                            field = `<input type="text" id="param-${i}-input" name="param-${i}-input"
                               value="" placeholder="${paramType}" required="true" class="form-control">`;
                        }
                        addParamRow(i, paramName, field);
                    }
                }

            });

        });

        function addParamRow(i, paramName, field) {
            let rowTemplate =
                `<div name="param${i}" class="row" style="margin-top: 10px">
                            <div class="col-md-6">
                               <p>${paramName}</p>
                            </div>

                            <div class="col-md-6">
                                ${field}
                            </div>
                        </div>`;

            rowTemplate += `</row>`;
            $('#paramGroup').append(rowTemplate);
        }

    </script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(3);">Volgende</button>
</div>
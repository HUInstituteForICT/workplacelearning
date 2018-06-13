<div class="modal-header">
    <h4 class="modal-title">@lang('querybuilder.step2.template')</h4>
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
                                <option value="{{$loop->index}}" name="{{$loop->index}}" {{ isset($data['chosen_template'])
                                && $data['chosen_template'] == $template ? "selected" : "" }}>{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p style="font-weight: bold;">Template Query</p>
                    <div class="test-query">
                        <textarea disabled rows="4" cols=100 maxlength="1000" id="tempQuery" name="tempQuery"
                                  class="form-control query-area"></textarea>
                    </div>

                    <div id="paramGroup" style="margin-top: 15px">
                        {{--JS will load parameters here--}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="final-query" style="margin-top: 15px">
                        <p style="font-weight: bold;">Query</p>
                        <textarea disabled rows="4" cols=100 maxlength="1000" id="realQuery" name="realQuery"
                                  class="form-control query-area"></textarea>
                    </div>

                    <button type="button" style="margin-top: 15px" class="btn btn-primary" onclick="onQueryTestClick()">
                        Test query
                    </button>
                    <div id="query-result" style="margin-top: 15px"></div>
                </div>

            </div>

        </div>


    </form>
    <script>
        let templates = JSON.parse('{!! json_encode($templates) !!}');
        let columnNames = JSON.parse('{!! json_encode($columnNames) !!}');
        let paramGroup = $('#paramGroup');

        $('.template-select').on("change", function () {
            let optionIndex = $(this).find('option:selected').attr("name");
            let query = templates[optionIndex]['query'];
            if (query != null) {
                query = query.replace(/@@/g, '\n');
                query = query.replace(/@/g, '\n');
                query = query.replace(/š/g, "'");
            }

            $('.query-area').val(query);

            let templateID = templates[optionIndex]['id'];
            $.getJSON("{{ route('template.parameters') }}/" + templateID, function (data) {
                paramGroup.empty();

                for (let i = 0; i < data.length; i++) {
                    let param = data[i];
                    let paramName = param['name'];
                    let paramType = param['type_name'].toString().toLowerCase();
                    let tableName = param['table'];
                    let columnName = param['column'];

                    let field = "";

                    if (paramType === 'column value') {

                        $.getJSON("{{ route('column-values') }}/" + tableName + "/" + columnName, function (data) {
                            field += `<select name="${i}" class="form-control table-select">`;

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
                                field += `<select name="${i}" class="form-control table-select">`;

                                for (let j = 0; j < colNames.length; j++) {
                                    let colName = colNames[j];
                                    field += "<option id='" + colName + "' class='field-${i}'>" + colName + "</option>";
                                }
                                field += `</select>`;
                            }
                        } else {
                            field = `<input type="text" id="param-${i}-input" name="${i}"
                               value="" placeholder="${paramType}" required="true" class="form-control field-${i}">`;
                        }
                        addParamRow(i, paramName, field);
                    }
                }
            });
        });

        $('.template-select').trigger("change");

        function addParamRow(i, paramName, field) {
            let rowTemplate =
                `<div name="param${i}" class="row" style="margin-top: 10px">
                            <div class="col-md-6">
                               <p>${paramName}</p>
                            </div>

                            <div class="col-md-6 field" name="${paramName}">
                                ${field}
                            </div>
                        </div>`;

            rowTemplate += `</row>`;

            paramGroup.append(rowTemplate);
        }

        paramGroup.on("change", '.field', onInputChange);

        function onInputChange() {
            let templateQuery = $('#tempQuery').val();

            paramGroup.children().find(".field").each(function () {
                if (this.children != null && this.children.length > 0) {
                    /* let paramIndex = $('.template-select').find(":selected").attr("name");
                     if (paramIndex != null) {

                     }*/

                    let val = $(this.children[0]).val();
                    let paramName = this.getAttribute("name");
                    if (val != null && paramName != null) {
                        templateQuery = templateQuery.replace("{" + paramName + "}", val);
                        $('#realQuery').val(templateQuery);
                    }
                }
            });
        }

        function onQueryTestClick() {
            let request = $.ajax({
                type: "POST",
                url: "/dashboard/builder/testQuery",
                data: $('#realQuery').val()
            });

            request.done(function (response) {
                if (response.length <= 0) {
                    $('#query-result').html(`<p>{{Lang::get('querybuilder.step2.no-data')}}</p>`)
                    return;
                }
                if (response['error'] != null) {
                    $('#query-result').html(`<p>{{Lang::get('querybuilder.step2.sql-error')}}</p>`)
                    return;
                }

                let headers = "";
                let headerNames = [];

                let first = response[0];
                for (let key in first) {
                    headers += `<th>` + key + `</th>`;
                    headerNames.push(key);
                }

                let rows = "";
                for (let i = 0; i < response.length; i++) {
                    let row = "<tr>";
                    let obj = response[i];

                    for (let index in headerNames) {
                        row += `<td>` + obj[headerNames[index]] + `</td>`;
                    }

                    row += "</tr>";
                    rows += row;
                }

                $('#query-result').html(`<table class="table table-striped">
                    <thead>
                        ${headers}
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>`);
            });
        }
    </script>

    <div class="modal-footer">
        <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">Vorige</button>
        <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
    </div>

</div>
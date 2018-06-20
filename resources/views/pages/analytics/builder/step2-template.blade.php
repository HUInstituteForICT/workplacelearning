<div class="modal-header">
    <h4 class="modal-title">@lang('querybuilder.step2.template')</h4>
</div>
<div class="modal-body">
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

                    <div id="tempDesc"></div>

                    <button type="button" style="margin-top: 15px" class="btn btn-default" onclick="showQueryClick()">
                        @lang('querybuilder.step2.show-query')
                    </button>

                    <div class="option-element">
                        <p style="font-weight: bold; margin-top: 15px;">Template Query</p>
                        <div class="test-query">
                        <textarea readonly rows="4" cols=100 maxlength="1000" id="tempQuery" name="tempQuery"
                                  class="form-control query-area"></textarea>
                        </div>
                    </div>

                    <div id="paramGroup" style="margin-top: 15px; margin-bottom: 15px;">
                        {{--JS will load parameters here--}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="final-query" class="option-element" style="margin-top: 15px">
                        <p style="font-weight: bold;">Query</p>
                        <textarea readonly rows="4" cols=100 maxlength="1000" id="realQuery" name="realQuery"
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
        let tempSelect = $('.template-select');
        let queryIsHidden = true;

        function showQueryClick() {
            if (queryIsHidden) {
                $('.option-element').show();
            } else {
                $('.option-element').hide();
            }
            queryIsHidden = !queryIsHidden;
        }

        $('.option-element').hide();

        tempSelect.on("change", function () {
            let optionIndex = $(this).find('option:selected').attr("name");
            let query = templates[optionIndex]['query'];
            if (query != null) {
                query = query.replace(/@@/g, '\n');
                query = query.replace(/@/g, '\n');
                query = query.replace(/š/g, "'");
            }

            $('.query-area').text(query);

            let template = templates[optionIndex];
            let templateID = template['id'];
            $('#tempDesc').text(template['description']);

            $.getJSON("{{ route('template.parameters') }}/" + templateID, function (data) {
                paramGroup.empty();
                let length = data.length;

                for (let i = 0; i < length; i++) {
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
                            addParamRow(i, length, paramName, field);
                        });

                    } else {
                        if (paramType === 'column') {
                            let colNames = columnNames[tableName];
                            if (colNames != null) {
                                field += `<select class="form-control table-select">`;

                                for (let j = 0; j < colNames.length; j++) {
                                    let colName = colNames[j];
                                    field += "<option id='" + colName + "' class='field-${i}'>" + colName + "</option>";
                                }
                                field += `</select>`;
                            }
                        } else {
                            if (paramType === "number") {
                                field = `<input type="number" maxlength="16" oninput="maxLengthCheck(this)" id="param-${i}-input"
                               value="" placeholder="${paramType}" required="true" class="form-control field-${i}">`;
                            } else if (paramType === "boolean") {
                                field += `<select class="form-control table-select">`;
                                field += `<option class='field-${i}'>` + `True` + `</option>`;
                                field += `<option class='field-${i}'>` + `False` + `</option>`;
                                field += `</select>`;
                            } else {
                                field = `<input type="text" id="param-${i}-input"
                               value="" placeholder="${paramType}" required="true" maxlength="20" class="form-control field-${i}">`;
                            }
                        }
                        addParamRow(i, length, paramName, field);
                    }
                }
            });
        });

        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
                object.value = object.value.slice(0, object.maxLength)
        }

        tempSelect.trigger("change");

        function addParamRow(i, totalSize, paramName, field) {
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

            if (i === (totalSize - 1)) {
                onInputChange();
            }
        }

        paramGroup.on("change", '.field', onInputChange);

        function onInputChange() {
            let templateQuery = $('#tempQuery').val();

            paramGroup.children().find(".field").each(function () {
                if (this.children != null && this.children.length > 0) {
                    let val = $(this.children[0]).val();
                    let paramName = this.getAttribute("name");

                    if (val != null && paramName != null) {
                        val = filterParameter(val);
                        templateQuery = templateQuery.replace("{" + paramName + "}", val);
                        $('#realQuery').val(templateQuery);
                        $(this.children[0]).val(val);
                    }
                }
            });
        }

        // Only allow letters and numbers and remove all spaces.
        function filterParameter(paramVal) {
            paramVal = paramVal.replace(/[^a-zA-Z0-9]+/g, '');
            paramVal = paramVal.replace(/ /g, '');
            return paramVal.toLowerCase() === 'or' ? '' : paramVal;
        }

        function handleQueryResponse(callback) {
            let request = $.ajax({
                type: "POST",
                url: "/dashboard/builder/testQuery",
                data: $('#realQuery').val()
            });

            request.done(function (response) {
                callback(response);
            });
        }

        function onQueryTestClick() {
            $('#query-result').html(`<p></p>`);
            handleQueryResponse(handleTestClick);
        }

        function handleTestClick(response) {
            let resultDiv = $('#query-result');
            if (response.length <= 0) {
                resultDiv.html(`<p>@lang('querybuilder.step2.no-data')</p>`);
                return;
            }
            if (response['error'] != null) {
                let error = response['error'];
                resultDiv.html(`<p>@lang('querybuilder.step2.sql-error') ${error}</p>`);
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
            let length = response.length < 3 ? response.length : 3;
            for (let i = 0; i < length; i++) {
                let row = "<tr>";
                let obj = response[i];

                for (let index in headerNames) {
                    row += `<td>` + obj[headerNames[index]] + `</td>`;
                }

                row += "</tr>";
                rows += row;
            }

            resultDiv.html(`<table class="table table-striped">
                    <thead>
                        ${headers}
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>`);
        }

        function nextStep() {
            handleQueryResponse(handleNextClick);
        }

        function handleNextClick(response) {
            if (response == null || response.length <= 0) {
                alert("@lang('querybuilder.step2.no-data')");
                return;
            }
            if (response['error'] != null) {
                alert("@lang('querybuilder.step2.sql-error')" + "(" + response['error'] + ")");
                return;
            }
            Wizard.step(4);
        }

    </script>

    <div class="modal-footer">
        <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">Vorige</button>
        <button type="button" class="btn btn-primary" onclick="nextStep()">Volgende</button>
    </div>

</div>
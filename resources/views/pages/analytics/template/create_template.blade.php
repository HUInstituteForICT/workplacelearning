@extends('layout.HUdefault')
@section('title', 'Template maken')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">

                @if ($template == null)
                    <h1>@lang('template.template_create')</h1>
                @else
                    <h1>@lang('template.edit_template')</h1>
                @endif

                <form action="{{ route('template.save') }}" method="post" id="saveForm">

                    <div class="form-group">
                        <input type="hidden" id="templateID" name="templateID" class="form-control"
                               value="{{ $template == null ? null : $template->id}}">

                        <label for="name">@lang('template.name')</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ $template == null ? old('name') : $template->name}}">

                        <label for="description" style="margin-top: 20px">@lang('template.description')</label>
                        <textarea rows="4" cols="50" maxlength="500" id="description" name="description"
                                  class="form-control">{{ $template == null ? old('description') : $template->description}}</textarea>

                        <label for="query" style="margin-top: 20px">Query</label>
                        <textarea rows="4" cols="50" onkeyup="loadParams()" maxlength="1000" id="query" name="query"
                                  class="form-control">{{ $template == null ? old('query') : $template->query}}</textarea>
                    </div>

                    <div class="form" id="paramGroup" style="margin: 20px 0px 20px 0px;">
                        {{--JS will load parameters here--}}
                    </div>

                    <button class="btn btn-primary" style="float: right" type="submit"
                            title="Opslaan">@lang('template.save')</button>
                    {{ csrf_field() }}
                </form>

                <form action="{{ route('template.index') }}" method="get">
                    <button class="btn btn-default" title="Terug">@lang('template.back')
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        let parameters = JSON.parse('{!! json_encode($parameters) !!}');
        let dataTypes = JSON.parse('{!! json_encode($typeNames) !!}');
        let columnNames = JSON.parse('{!! json_encode($columnNames) !!}');
        let tableNames = JSON.parse('{!! json_encode($tableNames) !!}');
        let lastCount = 0;
        let paramsLoaded = false;
        loadParams();

        function loadParams() {
            if (!paramsLoaded) {
                paramsLoaded = true;
                lastCount = parameters.length;

                for (let i = 0; i < parameters.length; i++) {
                    let param = parameters[i];
                    addParamRow(i, param['name'], param['type_name'], param['table'], param['column'], param['id']);
                }
            }

            let textArea = document.getElementById("query");
            let textValue = textArea.value;
            let count = countStringOccurrences(textValue, "{?}");

            if (count !== lastCount) {
                let paramGroup = document.getElementById('paramGroup');

                if (lastCount > count) {
                    for (let i = count; i <= lastCount; i++) {
                        let name = "param" + i;
                        let elements = document.getElementsByName(name);
                        if (elements != null) {
                            elements.forEach(elem => paramGroup.removeChild(elem));
                        }
                    }
                } else {
                    for (let i = lastCount; i < count; i++) {
                        addParamRow(i, null, null, null);
                    }
                }
                lastCount = count;
            }
        }

        function addParamRow(i, name, type_name, table, column, id = -1) {
            let options = [];

            if (type_name != null) {
                options.push(`<option value="${type_name}" selected>${type_name}</option>`);
            }
            for (let y = 0; y < dataTypes.length; y++) {
                let type = dataTypes[y];
                if (type_name != null && dataTypes[y] === type_name) {
                    continue;
                }
                options.push(`<option value="${type}">${type}</option>`);
            }

            let rowTemplate = `<div name="param${i}" class="row" style="margin-top: 10px">
                                    <input type="hidden" name="data[${i}]['id']" class="form-control" value="${id}">

                                    <div class="col-md-3">
                                        <input type="text" name="data[${i}]['parameter']" placeholder="param ${i + 1}" id="param${i}" required="true" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <select name="data[${i}]['type']" class="form-control column-type" data-counter="${i}">
                                                    ${options}
                                        </select>
                                    </div>

                                    <div class="col-md-3 table-div table-div-${i}"></div>
                                    <div class="col-md-3 column-div column-div-${i}"></div>
                                </div>`;

            $('#paramGroup').append(rowTemplate);

            if (name != null) {
                let paramID = 'param' + i;
                $("#" + paramID).val(name);
            }

            if (table != null) {
                let name = 'table-div-' + i;
                let selectName = 'table-data-' + i;
                $('.' + name).append(`<select name="data[${i}]['table']" class="form-control table-select ${selectName}" name="${selectName}"></select>`);

                let items = "<option id='" + table + "'>" + table + "</option>";

                for (let j = 0; j < tableNames.length; j++) {
                    let val = tableNames[j];
                    if (val !== table)
                        items += "<option id='" + val + "'>" + val + "</option>";
                }
                $("." + selectName).append(items);

                if (column != null) {
                    let name = 'column-div-' + i;
                    let selectName = 'column-data-' + i;
                    $('.' + name).append(`<select name="data[${i}]['column']" class="form-control col-select ${selectName}"></select>`);

                    let items = "<option id='" + column + "'>" + column + "</option>";
                    let columns = columnNames[table];
                    if (columns != null) {
                        for (let i = 0; i < columns.length; i++) {
                            let val = columns[i];
                            if (val !== column)
                                items += "<option id='" + val + "'>" + val + "</option>";
                        }
                    }
                    $("." + selectName).append(items);
                }
            }
        }

        $('#paramGroup').on("change", '.column-type', function () {
            $(this).parent().parent().find(".table-div").empty();
            $(this).parent().parent().find(".column-div").empty();

            let counter = $(this).attr("data-counter");

            if ($(this).val().startsWith("Column")) {

                $(this).parent().parent().find(".table-div").append(`<select name="data[${counter}]['table']" class="form-control table-select"></select>`);
                let self = this;

                if (tableNames.length > 0) {
                    $(self).parent().parent().find(".table-div select").empty();

                    let items = "";
                    for (let i = 0; i < tableNames.length; i++) {
                        let val = tableNames[i];
                        items += "<option id='" + val + "'>" + val + "</option>";
                    }

                    $(self).parent().parent().find(".table-div select").append(items);
                }

                if ($(this).val() === "Column Value") {
                    $(this).parent().parent().find(".column-div").append(`<select name="data[${counter}]['column']" class="form-control col-select"></select>`);
                    let self = this;

                    $(this).parent().parent().find('.table-select').on("change", function () {
                        loadColumns(self, $(this).val())
                    });
                }

                loadColumns(self, tableNames[0]);
            }
        });

        function loadColumns(self, table) {
            let colElement = $(self).parent().parent().find(".col-select");
            colElement.empty();

            let columns = columnNames[table];
            let items = "";
            if (columns != null) {
                for (let i = 0; i < columns.length; i++) {
                    let val = columns[i];
                    items += "<option id='" + val + "'>" + val + "</option>";
                }
            }

            colElement.append(items);
        }

        function countStringOccurrences(string, searchFor) {
            let count = 0,
                pos = string.indexOf(searchFor);

            while (pos > -1) {
                ++count;
                pos = string.indexOf(searchFor, ++pos);
            }
            return count;
        }

    </script>

@stop
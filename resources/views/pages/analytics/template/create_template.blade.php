@extends('layout.HUdefault')
@section('title', 'Template maken')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">

                @if ($template == null)
                    <h1>{{Lang::get('template.template_create')}}</h1>
                @else
                    <h1>{{Lang::get('template.edit_template')}}</h1>
                @endif

                <form action="{{ route('template.save') }}" method="post" id="saveForm">

                    <div class="form-group">
                        <label for="name">{{Lang::get('template.name')}}</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ $template == null ? old('name') : $template->name}}">
                    </div>

                    <div class="form-group">
                        <label for="query">Query</label>
                        <textarea rows="4" cols="50" onkeyup="loadParams()" maxlength="1000" id="query" name="query"
                                  class="form-control">{{ $template == null ? old('query') : $template->query}}</textarea>
                    </div>

                    <div class="form" id="paramGroup" style="margin-bottom: 20px">
                        {{--JS will load parameters here--}}
                    </div>

                    <button class="btn btn-primary" style="float: right" type="submit"
                            title="Opslaan">{{Lang::get('template.save')}}</button>
                    {{ csrf_field() }}
                </form>

                <form action="{{ route('template.index') }}" method="get">
                    <button class="btn btn-default" title="Terug">{{Lang::get('template.back')}}
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        let parameters = JSON.parse('{!! json_encode($parameters) !!}');
        let lastCount = 0;
        let dataTypes = JSON.parse('{!! json_encode($typeNames) !!}');
        let paramsLoaded = false;
        loadParams();

        function loadParams() {
            if (!paramsLoaded) {
                paramsLoaded = true;
                lastCount = parameters.length;

                for (let i = 0; i < parameters.length; i++) {
                    let param = parameters[i];
                    let name = param['name'];
                    let type_name = param['type_name'];
                    let table = param['table'];
                    let column = param['column'];
                    addParamRow(i, name, type_name, table, column);
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

        function addParamRow(i, name, type_name, table, column) {
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

            let rowTemplate = `<div name="param${i}" class="row">
                                    <div class="col-md-3">
                                        <input type="text" placeholder="param ${i + 1}" id="param${i}" required="true" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control column-type">
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
                $('.' + name).append(`<select class="form-control table-select ${selectName}" name="${selectName}"></select>`);

                $.getJSON("{{ route('template.tables') }}", function (data) {
                    let items = "<option id='" + table + "'>" + table + "</option>";

                    $.each(data, function (key, val) {
                        if (val !== table)
                            items += "<option id='" + val + "'>" + val + "</option>";
                    });
                    $("." + selectName).append(items);
                });

                if (column != null) {
                    let name = 'column-div-' + i;
                    let selectName = 'column-data-' + i;
                    $('.' + name).append(`<select class="form-control col-select ${selectName}"></select>`);

                    $.getJSON("{{ route('template.columns') }}/" + table, function (data) {
                        let items = "<option id='" + column + "'>" + column + "</option>";;

                        $.each(data, function (key, val) {
                            if (val !== column)
                                items += "<option id='" + val + "'>" + val + "</option>";
                        });
                        $("." + selectName).append(items);
                    });
                }
            }
        }

        $('#paramGroup').on("change", '.column-type', function () {
            $(this).parent().parent().find(".table-div").empty();
            $(this).parent().parent().find(".column-div").empty();

            if ($(this).val().startsWith("Column")) {

                $(this).parent().parent().find(".table-div").append(`<select class="form-control table-select"></select>`);
                var self = this;

                $.getJSON("{{ route('template.tables') }}", function (data) {
                    let items = "";

                    $.each(data, function (key, val) {
                        items += "<option id='" + val + "'>" + val + "</option>";
                    });

                    $(self).parent().parent().find(".table-div select").empty();
                    $(self).parent().parent().find(".table-div select").append(items);
                });

                if ($(this).val() === "Column Value") {
                    $(this).parent().parent().find(".column-div").append(`<select class="form-control col-select"></select>`);
                    let self = this;

                    $(this).parent().parent().find('.table-select').on("change", function () {
                        $.getJSON("{{ route('template.columns') }}/" + $(this).val(), function (data) {
                            $(self).parent().parent().find(".col-select").empty();

                            let items = "";
                            $.each(data, function (key, val) {
                                items += "<option id='" + val + "'>" + val + "</option>";
                            });
                            $(self).parent().parent().find(".col-select").append(items);
                            console.log($(self).parent().parent().find(".col-select").length);
                        });
                    });

                }
            }
        });

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
@extends('layout.HUdefault')
@section('title', 'Template maken')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h1>{{Lang::get('template.template_create')}}</h1>

                <form action="{{ route('template.save') }}" method="post" id="saveForm">

                    <div class="form-group">
                        <label for="name">{{Lang::get('template.name')}}</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="query">Query</label>
                        <textarea rows="4" cols="50" onkeyup="loadParams()" maxlength="1000" id="query" name="query"
                                  class="form-control">{{ old('query') }}</textarea>
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
        let lastCount = 0;
        let dataTypes = JSON.parse('{!! json_encode($typeNames) !!}');

        function loadParams() {
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

                        let options = [];
                        for (let y = 0; y < dataTypes.length; y++) {
                            options.push(`<option value="${dataTypes[y]}">${dataTypes[y]}</option>`);
                        }

                        let rowTemplate = `<div name="param${i}" class="row">
                                            <div class="col-md-3"><input type="text" placeholder="param ${i + 1}" id="param${i}" required="true" class="form-control"></div>

                                            <div class="col-md-3"><select class="form-control">
                                                    ${options}
                                                </select></div>
                                            <div class="col-md-3 column-value"></div>
                                            <div class="col-md-3 display-column"></div>
                                        </div>`;

                        $('#paramGroup').append(rowTemplate);

                        $('#paramGroup').on("change", 'select', function () {
                            console.log($(this).val());
                            $(this).parent().parent().find(".column-value input").remove();
                            $(this).parent().parent().find(".display-column input").remove();

                            if ($(this).val().startsWith("Column")) {
                                //TODO: change to select list and load all the database tables
                                $(this).parent().parent().find(".column-value").append(`<input placeholder="Table" class="form-control">`);
                                if ($(this).val() === "Column Value") {
                                    //TODO: change to select list and load all the database columns for the selected table
                                    $(this).parent().parent().find(".display-column").append(`<input placeholder="Display Column" class="form-control">`);
                                }
                            }
                        });
                    }
                }
                lastCount = count;
            }
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
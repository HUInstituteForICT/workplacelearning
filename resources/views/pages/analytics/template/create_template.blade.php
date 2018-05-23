@extends('layout.HUdefault')
@section('title', 'Template maken')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1>{{Lang::get('template.template_create')}}</h1>

            <form action="{{ route('template.save') }}" method="post" id="saveForm">

                <div class="form-group">
                    <label for="name">{{Lang::get('template.name')}}</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="query">Query</label>
                    <textarea rows="4" cols="50" onkeyup="loadParams()" maxlength="100" id="query" name="query"
                              class="form-control"></textarea>
                </div>

                <div class="form-group" id="paramGroup">
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

    <script>
        let lastCount = 0;
        let dataTypes = ["String", "Integer", "Date"];

        function loadParams() {
            let textArea = document.getElementById("query");
            let textValue = textArea.value;
            let countLeft = (textValue.match(/{/g) || []).length;
            let countRight = (textValue.match(/}/g) || []).length;
            let count = countLeft < countRight ? countLeft : countRight;

            if (count !== lastCount) {
                let paramGroup = document.getElementById('paramGroup');
                for (let i = 0; i < lastCount; i++) {
                    let name = "param" + i;
                    let elements = document.getElementsByName(name);
                    if (elements != null) {
                        elements.forEach(elem => paramGroup.removeChild(elem));
                    }

                    let selectLists = document.getElementsByName(name + "list");
                    if (selectLists != null) {
                        selectLists.forEach(list => paramGroup.removeChild(list));
                    }
                }

                lastCount = count;
                for (let i = 0; i < count; i++) {
                    let name = "param" + i;

                    let inputField = document.createElement("input");
                    inputField.setAttribute("type", "text");
                    inputField.setAttribute("placeholder", "param " + (i + 1));
                    inputField.setAttribute("name", name);
                    inputField.setAttribute("id", name);
                    inputField.setAttribute("required", "true");
                    inputField.style.marginTop = "6px";
                    inputField.style.cssFloat = "left";
                    inputField.className = "form-control";

                    let selectList = document.createElement("select");
                    selectList.setAttribute("name", name + "list");
                    selectList.className = "form-control";
                    selectList.style.cssFloat = "right";
                    selectList.style.marginTop = "2px";

                    for (let y = 0; y < dataTypes.length; y++) {
                        let option = document.createElement("option");
                        option.value = dataTypes[y];
                        option.text = dataTypes[y];
                        selectList.appendChild(option);
                    }

                    document.getElementById('paramGroup').appendChild(inputField);
                    document.getElementById('paramGroup').appendChild(selectList);
                }
            }
        }
    </script>

@stop
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
                        <input type="text" id="name" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="query">Query</label>
                        <textarea rows="4" cols="50" onkeyup="loadParams()" maxlength="1000" id="query" name="query"
                                  class="form-control"></textarea>
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
        let dataTypes = ["String", "Integer", "Date"];

        function loadParams() {
            let textArea = document.getElementById("query");
            let textValue = textArea.value;
            let count = countStr(textValue, "{?}");

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
                        let name = "param" + i;

                        let inputField = document.createElement("input");
                        inputField.setAttribute("type", "text");
                        inputField.setAttribute("placeholder", "param " + (i + 1));
                        inputField.setAttribute("id", name);
                        inputField.setAttribute("required", "true");
                        inputField.style.cssFloat = "left";
                        inputField.className = "form-control";

                        let selectList = document.createElement("select");
                        selectList.className = "form-control";
                        selectList.style.cssFloat = "right";
                        selectList.style.marginTop = "2px";

                        for (let y = 0; y < dataTypes.length; y++) {
                            let option = document.createElement("option");
                            option.value = dataTypes[y];
                            option.text = dataTypes[y];
                            selectList.appendChild(option);
                        }

                        let row = document.createElement("div");
                        row.setAttribute("name", name);
                        row.className = "row";

                        let div1 = getDiv();
                        div1.appendChild(inputField);

                        let div2 = getDiv();
                        div2.appendChild(selectList);

                        row.append(div1);
                        row.append(div2);
                        row.append(getDiv());

                        document.getElementById('paramGroup').appendChild(row);
                    }
                }
                lastCount = count;
            }
        }

        function countStr(string, searchFor) {
            let count = 0,
                pos = string.indexOf(searchFor);

            while (pos > -1) {
                ++count;
                pos = string.indexOf(searchFor, ++pos);
            }
            return count;
        }

        function getDiv() {
            let div = document.createElement("div");
            div.className = "col-md-3";
            return div;
        }

    </script>

@stop
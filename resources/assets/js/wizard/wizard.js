var Wizard =  {

    step: function(id) {

        var request = $.ajax({

            type: "POST",

            url: "/dashboard/builder/step/" + id + "/",

            data: $("#wizard-form").serialize(),

        });

        request.done(function( response ) {

            var data = JSON.parse(response);

            if(data.step == 5) {

                window.location.reload();

            } else {

                if(data.error != undefined) {

                    var errors = '';

                    for(var e in data.error) {

                        errors += `<li>${data.error[e]}</li>`;
                    }

                    $('#wizard-error').html(`<ul>${errors}</ul>`);
                } else {

                    $('#QueryBuilder').load("/dashboard/builder/step/" + data.step + "/", function(response, status, xhr) {

                        Wizard['step_' + data.step]();

                        if(xhr.status == 403) {

                            window.location.reload();
                        }

                    });
                }
            }

        });
    },

    open: function() {

        $('#QueryBuilder').load("/dashboard/builder/step/0/");
    },

    step_2: function() {

        $('#analysis_entity').on('change', function(data) {
            $.getJSON( "/dashboard/builder/relations/" + $(this).val(), function( data ) {
                var items = "";
                $.each( data, function( key, val ) {
                    items += ` <div class="form-check">
                <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_${key}" value="${key}">
                <label class="form-check-label" for="analysis_relations_${key}">
                    ${val}
                            </label>
                        </div>`;
                });

                $('.relations').html(items);
            });
        });
    },

    step_3: function() {

        this.resetListeners();

        var tables;

        $.getJSON("/dashboard/builder/tables/", function (data) {

            tables = data;
        });

        $('.query-add-filter').on('click', function () {

            var previous = $('.query-filter-container .row:last-child').data('id');

            if(isNaN(previous)) {

                previous = -1;
            }

            $('.query-filter-container').append(`
            <div class="form-group row" data-id="${previous+1}">
                <div class="col-md-1" style="width: 25px;"><a href="#" class="query-delete-filter" style="line-height: 34px; text-decoration: none;">X</a></div>
                <div class="col-md-3">
                    <select class="form-control query-data-table" name="query_filter[${previous+1}][table]">
                       ${Object.keys(tables).map(function (key) {
                            return "<option value='" + key + "'>" + tables[key] + "</option>"
                        })}
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_filter[${previous+1}][column]"></select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-filter-type" name="query_filter[${previous+1}][type]">
                        <option value="equals" selected>${Lang.get('querybuilder.step3.filter-equals')}</option>
                        <option value="largerthan">${Lang.get('querybuilder.step3.filter-largerthan')}</option>
                        <option value="smallerthan">${Lang.get('querybuilder.step3.filter-smallerthan')}</option>
                        <option value="group">${Lang.get('querybuilder.step3.filter-groupby')}</option>
                    </select>
                </div>
                <div class="col-md-2" style="width: 12%;">
                    <input name="query_filter[${previous+1}][value]" class="form-control query-filter-value" placeholder="${Lang.get('querybuilder.step3.value')}">
                </div>
            </div>`);

            Wizard.resetListeners();
            $('.query-filter-container .row:last-child .query-data-table').change();
        });

    },

    step_4: function() {

        $('#type_id, #x_axis, #y_axis').on('change', function() {

            $('.chart-container').load('/dashboard/builder/chart', {
                'type': $('#type_id').val(),
                'x':  $('#x_axis').val(),
                'y':  $('#y_axis').val(),
                'name': $('#name').val()
            });
        });

        $('#type_id').change();
    },

    resetListeners: function() {

        $('.query-data-table').off('change');
        $('.query-delete-filter').off('click');

        $('.query-data-table').on('change', function () {

            var self = this;

            $.getJSON("/dashboard/builder/columns/" + this.value, function (data) {
                $(self).parent().parent().find(".query-data-column").empty();

                let items = "";
                $.each(data, function (key, val) {
                    items += "<option id='" + val + "'>" + val + "</option>";
                });
                $(self).parent().parent().find(".query-data-column").append(items);
            });
        });

        $('.query-filter-type').on('change', function () {

           if($(this).val() == "group") {

               $(this).parent().parent().find('.query-filter-value').css('display', 'none');
           } else {
               $(this).parent().parent().find('.query-filter-value').css('display', 'block');
           }
        });

        $('.query-delete-filter').on('click', function () {

            $(this).parent().parent().remove();
        });
    },

    executeBuilderQuery: function(data) {

        var request = $.ajax({

            type: "POST",

            url: "/dashboard/builder/query/",

            data: $("#wizard-form").serialize(),

        });

        request.done(function( response ) {

            var responseData = response;

            if(responseData.error != undefined) {

                alert(responseData.error);
            }

            var headers = "";

            for(var header in responseData[0]) {

                headers += `<th style="max-width: 111px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">${header}</th>`;
            }

            var rows = "";

            for(var i = 0; i < responseData.length; i++) {

                var row = "<tr>";

                for(var column in responseData[i]) {

                    row += `<td style="max-width: 111px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">${responseData[i][column]}</td>`;
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
    },

    executeCustomBuilderQuery: function(data) {

        var request = $.ajax({

            type: "POST",

            url: "/dashboard/builder/testQuery",

            data: data,

        });

        request.done(function( response ) {

            var responseData = response;

            var headers = "";

            for(var header in responseData[0]) {

                headers += `<th>${header}</th>`;
            }

            var rows = "";

            for(var i = 0; i < 6; i++) {

                var row = "<tr>";

                for(var column in responseData[i]) {

                    row += `<td>${responseData[i][column]}</td>`;
                }

                row += "</tr>";
                rows += row;
            }

            $('#dataCustomQuery').html(`<table class="table table-striped">
                    <thead>
                        ${headers}
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>`);

        });
    }
}

window.Wizard = Wizard;
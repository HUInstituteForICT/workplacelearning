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

                $('#QueryBuilder').load("/dashboard/builder/step/" + data.step + "/", function(response, status, xhr) {

                    Wizard['step_' + data.step]();

                    if(xhr.status == 403) {

                        window.location = '/';
                    }

                    if(data.message != undefined) {

                        $('#wizard-error').html(data.message);
                    }

                    //Wizard['add_step_' + data.step](data);
                });
            }

        });
    },

    open: function() {

        $('#QueryBuilder').load("/dashboard/builder/step/0/");
    },

    step_3: function() {

        this.resetListeners();

        var tables;

        $.getJSON("/dashboard/builder/tables/", function (data) {

            tables = data;
        });

        $('.query-add-filter').on('click', function (e) {

            var previous = $('.query-filter-container .row:last-child').data('id');

            $('.query-filter-container').append(`
            <div class="form-group row" data-id="${previous+1}">
                <div class="col-md-1" style="width: 25px;"><a href="#" class="query-delete-filter" style="line-height: 34px; text-decoration: none;">X</a></div>
                <div class="col-md-2">
                    <select class="form-control query-data-type" name="query_filter[${previous+1}][type]">
                        <option>Table Filter</option>
                        <option>Between</option>
                        <option>Equals</option>
                        <option>Larger than</option>
                        <option>Smaller than</option>
                        <option>Group by</option>
                        <option>Limit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-table" name="query_filter[${previous+1}][table]">
                       ${Object.keys(tables).map(function (key) {
                            return "<option value='" + tables[key] + "'>" + tables[key] + "</option>"
                        })}
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control query-data-column" name="query_filter[${previous+1}][column]"></select>
                </div>
                <div class="col-md-2">
                    <!--select class="form-control" name="query_data[]" id="analysis_entity">
                        <option>Value</option>
                    </select-->
                    <input name="query_filter[${previous+1}][value]" class="form-control" placeholder="Value">
                </div>
            </div>`);

            Wizard.resetListeners();
            $('.query-filter-container .row:last-child .query-data-table').change();
        });

        $('.query-data-table').change();
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

        $('.query-delete-filter').on('click', function () {

            $(this).parent().parent().remove();
        });
    },

    executeBuilderQuery: function(data) {

        var request = $.ajax({

            type: "POST",

            url: "/dashboard/builder/step/" + id + "/",

            data: $("#wizard-form").serialize(),

        });

        request.done(function( response ) {

            var responseData = JSON.parse(response);

        });
    }
}

window.Wizard = Wizard;
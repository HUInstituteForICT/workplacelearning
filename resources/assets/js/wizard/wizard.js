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

        $('.query-data-table').on('change', function () {

            var self = this;

            $.getJSON("/dashboard/builder/columns/" + this.value, function (data) {
                $(self).parent().parent().find(".query-data-column").empty();
                console.log($(self).parent().parent().find(".query-data-column"));
                let items = "";
                $.each(data, function (key, val) {
                    items += "<option id='" + val + "'>" + val + "</option>";
                });
                $(self).parent().parent().find(".query-data-column").append(items);
            });
        });
    }
}

window.Wizard = Wizard;
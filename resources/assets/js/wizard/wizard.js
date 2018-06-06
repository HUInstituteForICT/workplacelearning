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
    }
}

window.Wizard = Wizard;
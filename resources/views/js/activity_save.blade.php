<script>

    const form = document.getElementById('taskForm');
    const errorElement = document.getElementById('taskFormError');

    form.onsubmit = submitTaskForm;



    /**
     * Callback on form submit
     * @param event {Event}
     *
     */
    function submitTaskForm(event) {
        event.preventDefault();

        const data = new FormData(form);
        console.log(data);
        const url = form.getAttribute('action');

        errorElement.style = 'display:none;';


        submit(data, url)
            .fail(function (error) {
                if (error.status === 422) {
                    handleFormErrors(error);
                } else {
                    handleFormErrors({
                        responseJSON: {
                            message: '',
                            errors: {general: ['Encountered unknown error while submitting the activity, please try again later.']}
                        }
                    });
                }
            })
            .done(function (response) {
                console.log(response);
                if(response.status === 'success') {
                    if(response.hasOwnProperty('url')) {
                        window.location.href = response.url;
                    }
                }
            });


        return false;
    }

    /**
     * Render the errors
     * @param error {object}
     *
     */
    function handleFormErrors(error) {
        console.log(error);
        const normalizedErrors = [];
        Object.keys(error.responseJSON.errors).forEach(function(field) {
            error.responseJSON.errors[field].forEach(function(errorEntry) {
                normalizedErrors.push('<p>' + errorEntry + '</p>');
            })
        });

        errorElement.innerHTML = normalizedErrors.join(' ');
        errorElement.style = 'display:block;';
    }


    /**
     * Send AJAX request for form
     * @param data {object} fields and values to be submitted
     * @param url {string} to submit form to
     *
     */
    function submit(data, url) {
        return $.ajax({
            type: "POST",
            url: url,
            data: data,
            // contentType: 'application/json'
            processData: false,
            contentType: false,
            dataType: 'json'

        })
    }



</script>
<div class="modal-header">
    <h4 class="modal-title">{{__('dashboard.details-title')}}</h4>
</div>
<div class="modal-body" style="height: 450px; overflow-y:scroll;">
    {{__('dashboard.category')}}: {{$label}}
    <ul class="list-group" id="values" style="margin-top: 10px">

    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
</div>

<script>

    $.getJSON("{{ route('charts-details', $idLabel) }}", function (data) {
        if (data.length <= 0) {
            $('#values').append(`<p> @lang('dashboard.no-descriptions') </p>`);
            return;
        }

        let duration = '';

        if(data[0]['duration'] != undefined)
            duration = `<th scope="col"> @lang('dashboard.hour') </th>`;

        $('#values').append(
            `
            <a id='description-copier' title='@lang("dashboard.copy-descriptions-title")'>@lang('dashboard.copy-descriptions')</a>
            <br/>
            <table class="table" id="table">
                 <thead>
                    <tr>
                      <th scope="col"> @lang('dashboard.description') </th>
                      ${duration}
                    </tr>
                  </thead>
                  <tbody id="table_body">

                  </tbody>
            </table>
            `
        );

        const descriptionsCollection = [];

        data.forEach(function(entry) {
            let desc = entry['description'];
            let duration = entry['duration'];

            descriptionsCollection.push(desc);

            let template = `<tr>
                    <td>${desc}</td>
                    <td>${duration}</td>
                </tr>`;

            if(duration === undefined) {
                template = `<tr>
                    <td>${desc}</td>
                </tr>`;
            }

            $('#table_body').append(template);
        });

        $('#description-copier').click(function() {
            navigator.clipboard.writeText(descriptionsCollection.join("\t"))
                .catch(function() {
                    $('#description-copier').text('@lang("dashboard.copy-descriptions-error")');
                })
                .then(function() {
                    $('#description-copier').text('@lang("dashboard.copy-descriptions-success")');
                })
                .finally(function() {
                    setTimeout(function() {
                        $('#description-copier').text('@lang("dashboard.copy-descriptions")');
                    }, 5000);
                })
            ;
        })
    });

</script>
<div class="modal-header">
    <h4 class="modal-title">{{Lang::get('dashboard.details-title')}}</h4>
</div>
<div class="modal-body" style="height: 450px">
    {{Lang::get('dashboard.category')}}: {{$label}}
    <ul class="list-group" id="values" style="margin-top: 10px">

    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
</div>

<script>

    $.getJSON("{{ route('charts-details', $idLabel) }}", function (data) {
        if (data.length <= 0) {
            $('#values').append(`<p> {{Lang::get('dashboard.no-descriptions')}} </P>`);
            return;
        }
        $('#values').append(
            `<table class="table" id="table">
                 <thead>
                    <tr>
                      <th scope="col"> {{Lang::get('dashboard.description')}} </th>
                      <th scope="col"> {{Lang::get('dashboard.hour')}} </th>
                    </tr>
                  </thead>
                  <tbody id="table_body">

                  </tbody>
            </table>`
        );

        data.forEach(function(entry) {
            let desc = entry['description'];
            let duration = entry['duration'];

            $('#table_body').append(
                `<tr>
                    <td>${desc}</td>
                    <td>${duration}</td>
                </tr>`
            );
        });
    });

</script>
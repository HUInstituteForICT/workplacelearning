<div class="modal-header">
    <h4 class="modal-title">Chart details</h4>
</div>
<div class="modal-body" style="height: 450px">
    Category: {{$label}}
    <ul class="list-group" id="values" style="margin-top: 10px">

    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
</div>

<script>

    $.getJSON("{{ route('charts-details', $label) }}", function (data) {
        if (data.length <= 0) {
            $('#values').append(`<p> No data found. </P>`);
            return;
        }

        data.forEach(function(entry) {
            let item = entry['description'];
            let listItem = `<li class="list-group-item">${item}</li>`;
            $('#values').append(listItem);
        });
    });

</script>

{{--TODO: Lang--}}
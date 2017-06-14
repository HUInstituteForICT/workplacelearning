<table>
    <tbody>
    <?php
    $shown_headers = false;
    ?>
    @foreach($data['data'] as $entry)
        @if(!$shown_headers)
            <tr>
                @foreach(array_keys((array)$entry) as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
            <?php $shown_headers = true; ?>
        @endif
        <tr>
            @foreach(array_values((array)$entry) as $v)
                <td>{{ $v }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

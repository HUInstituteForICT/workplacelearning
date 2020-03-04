<strong>{{ $name }} bij {{ $workplace }}</strong><br/>

<br/>
<strong>Omschrijvingen</strong>:<br/>


@if(count($descriptions) > 0)
    <div style="margin-left: 10px;">
        @foreach($descriptions as $description)
            <div style="border-left: 5px solid #00A1E2; margin: 10px 0; padding: 5px; box-shadow: 0px 1px 2px rgba(0,0,0,.5); border-radius: 4px;">
                <p>
                    {{ $description }}
                </p>
            </div>
        @endforeach

    </div>
@endif


<strong>Begeleidingsvraag</strong>:<br/>
<div style="margin: 10px 0; padding: 5px; box-shadow: 0px 1px 2px rgba(0,0,0,.5); border-radius: 4px;">
    <p>
        {{ $question }}
    </p>
</div>




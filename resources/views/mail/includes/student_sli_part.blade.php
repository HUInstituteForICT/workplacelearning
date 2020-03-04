<div class="panel panel-default">
    <div class="panel-body">
        @include('mail.includes.common_sli_part')
    </div>
</div>


<div style="max-width: 800px;">
    <div style="border-left: 5px solid #3ede66; margin: 10px 0; padding: 5px; box-shadow: 0px 1px 2px rgba(0,0,0,.5); border-radius: 4px;">
        <strong>{{ $teacherName }} reageerde</strong>:<br/>
        <p>
            {{ $answer }}
        </p>
    </div>
</div>

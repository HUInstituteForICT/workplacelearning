@include('mail.includes.digest_header')

Beste {{ $name }},
<br/>
<br/>
Onderstaande begeleidingsvragen zijn beantwoord door je stagedocent.
<br/>
<br/>

@foreach($renderedNotifications as $notification)
{{--  We can render raw because we already escaped it during pre-render when notification was saved  --}}
    {!! $notification !!}
@endforeach

<br/>

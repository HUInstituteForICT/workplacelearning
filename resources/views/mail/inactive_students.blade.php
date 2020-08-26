@include('mail.includes.digest_header')

Beste {{ $name }},
<br/>
<br/>
Onderstaande studenten hebben al langer dan vijf dagen geen uren geregistreerd. Er wordt geadviseerd contact op te nemen met deze studenten om te vragen waarom zij geen uren registreren
<br/>


@foreach($renderedNotifications as $notification)
    <div style="margin: 10px;">
        {!! $notification !!}
    </div>
@endforeach

<br/>
<br/>
Wanneer u geen e-mails meer wilt ontvangen of de frequentie van deze e-mails wilt wijzigen kunt u dit instellen in uw profielvoorkeuren in de Stage-App.

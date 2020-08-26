@include('mail.includes.digest_header')

Beste {{ $name }},
<br/>
<br/>
Onderstaande begeleidingsvragen zijn binnengekomen via de Stage-app.
<br/>


@foreach($renderedNotifications as $notification)
    <div style="margin: 10px; border-bottom: 3px solid gray">
        {!! $notification !!}
    </div>
@endforeach

<br/>
<br/>
Wanneer u geen e-mails meer wilt ontvangen of de frequentie van deze e-mails wilt wijzigen kunt u dit instellen in uw profielvoorkeuren in de Stage-App.

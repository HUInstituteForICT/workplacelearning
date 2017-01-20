<html>
<head>
    <title>Wachtwoord reset link</title>
</head>
<body>
<p>
    Beste Student,<br /><br />

    Je krijgt deze email omdat je aan hebt gegeven dat je je wachtwoord bent vergeten voor de website werkplekleren.hu.nl.<br /><br />

    Je kan een nieuw wachtwoord instellen met de volgende link: <a href="{{ URL::to('/password/reset', array('token'=>$token), true) }}" target="_blank">Reset wachtwoord</a>.
</p>
<p>Heb je geen nieuw wachtwoord aangevraagd? dan kan je deze email als niet verzonden beschouwen.</p>
</body>
</html>
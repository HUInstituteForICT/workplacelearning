<html>
<head>
    <title>{{ Lang::get('passwords.password-reset-link') }}</title>
</head>
<body>
<p>
    {{ Lang::get('passwords.dear-student') }},<br /><br />

    {{ Lang::get('passwords.reset-link-why') }} werkplekleren.hu.nl.<br /><br />

    {{ Lang::get('passwords.reset-link-how') }}: <a href="{{ URL::to('/password/reset', array('token'=>$token), true) }}" target="_blank">{{ Lang::get('passwords.reset_password') }}</a>.
</p>
<p>{{ Lang::get('passwords.reset-not-me') }}</p>
</body>
</html>
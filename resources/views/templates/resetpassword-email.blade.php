<html>
<head>
    <title>{{ __('passwords.password-reset-link') }}</title>
</head>
<body>
<p>
    {{ __('passwords.dear-student') }},<br /><br />

    {{ __('passwords.reset-link-why') }} werkplekleren.hu.nl.<br /><br />

    {{ __('passwords.reset-link-how') }}: <a href="{{ URL::to('/password/reset', array('token'=>$token), true) }}" target="_blank">{{ __('passwords.reset_password') }}</a>.
</p>
<p>{{ __('passwords.reset-not-me') }}</p>
</body>
</html>
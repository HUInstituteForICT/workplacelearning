<html>
<head></head>
<body>
<p>
    <b>Naam Student:</b> {{ $student_name }}<br />
    <b>Opleiding:</b> {{ $education->ep_name }}<br />
    <b>Email:</b> {{ $student_email }}<br />
    --------<br />
    <b>Betreft:</b> {{ $subject }}<br />
    <b>Detail:</b>
</p>
<p>
    {{ $content }}
</p>
</body>
</html>
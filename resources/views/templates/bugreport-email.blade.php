<html>
<head></head>
<body>
<p>
        <table>
        <tbody>
        <tr>
                <td><strong>{{ __('dashboard.name') }} student:</strong></td>
                <td>{{ $student_name }}</td>
        </tr>
        <tr>
                <td><strong>{{ __('general.program') }}</strong></td>
                <td>{{ $education->ep_name }}</td>
        </tr>
        <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $student_email }}</td>
        </tr>
        <tr>
                <td><strong>{{ __('general.subject') }}:</strong></td>
                <td>{{ $subject }}</td>
        </tr>
        </tbody>
</table>
<h4>Onderwerp van het bugrapport:</h4>
<p>
    {{ $bug_subject }}
</p>

<h4>Omschrijving:</h4>
<p>
    {{ $content }}
</p>
</body>
</html>
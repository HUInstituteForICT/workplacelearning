<?php
/**
 * This file (errors.php) was created on 10/28/2016 at 12:53.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */


return [
    'http' => [
        '404' => [
            'title'     => 'Pagina bestaat niet',
            'message'   => 'Deze pagina kon niet worden gevonden. (Error code: 404)',
        ], 
        '500' => [
            'title'     => 'Oeps...',
            'message'   => 'Er heeft een interne server error plaatsgevonden. (Error code: 500)',
        ],
        '503' => [
            'title'     => 'Service Onbeschikbaar',
            'message'   => 'De service die u heeft verzocht is tijdelijk onbeschikbaar. Probeer het op een later tijdstip nog een keer. (Error code: 503)',
        ],
    ],
    'returnhome' => 'Terug naar Dashboard',
];
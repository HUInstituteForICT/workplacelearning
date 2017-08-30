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
            'title'     => 'In onderhoud',
            'message'   => 'Deze website ondergaat momenteel onderhoud, kom later terug.',
        ],
    ],
    'returnhome' => 'Terug naar Dashboard',
];
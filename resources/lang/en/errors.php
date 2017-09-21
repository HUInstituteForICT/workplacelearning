<?php
/**
 * This file (errors.php) was created on 10/28/2016 at 12:53.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

return [
    'http' => [
        '404' => [
            'title'     => 'This page does not exist',
            'message'   => 'This page could not be found, or does not exist. (Error code: 404)',
        ],
        '500' => [
            'title'     => 'Oops...',
            'message'   => 'An Internal server error occured. (Error code: 500)',
        ],
        '503' => [
            'title'     => 'Maintenance',
            'message'   => 'This website is currently undergoing maintenance, come back later.',
        ],
    ],
    'returnhome' => 'Back to Dashboard',
];
?>
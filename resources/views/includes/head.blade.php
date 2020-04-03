<?php
/**
 * This file (head.blade.php) was created on 04/23/2016 at 15:49.
 * Author: Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
        <meta charset="utf-8">
        <meta name="author" content="HU University of Applied Sciences">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <!-- Jquery -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <!-- Jquery Touch Punch for Mobile Jquery Support -->
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="{{ secure_asset('js/HUhelper.js') }}"></script>
        <meta name="_token" content="{!! csrf_token() !!}" />
        <!-- Bootstrap -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.45/css/bootstrap-datetimepicker.min.css">

        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Moment Time localization -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
        <!-- Datetime Picker -->
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.45/js/bootstrap-datetimepicker.min.js"></script>
        <!-- Chart.JS library -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
        <!-- Font Awesome libary -->
        <script src="//use.fontawesome.com/ed94d838ea.js"></script>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/animations.css') }}">

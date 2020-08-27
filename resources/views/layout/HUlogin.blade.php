<?php
/**
 * This file (HUlogin.blade.php) was created on 05/26/2016 at 15:56.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="author" content="HU University of Applied Sciences">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Required stylesheets -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>@yield('title') - {{ __('general.werkplekleren') }}</title>
    <style>
        .main{
            margin-top: 70px;
        }
        h1.title {
            font-size: 50px;
            font-family: 'Passion One', cursive;
            font-weight: 400;
        }
        hr{
            width: 10%;
            color: #fff;
        }
        .form-group{
            margin-bottom: 15px;
        }

        label{
            margin-bottom: 15px;
        }
        input,
        input::-webkit-input-placeholder {
            font-size: 11px;
            padding-top: 3px;
        }
        .main-login{
            border: 2px solid
            background-color: #fff;
            /* shadows and rounded borders */
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -webkit-box-shadow: 0px 0px 1px 0px rgba(0,0,0,1);
            -moz-box-shadow: 0px 0px 1px 0px rgba(0,0,0,1);
            box-shadow: 0px 0px 1px 0px rgba(0,0,0,1);

        }
        .main-center{
            margin-top: 30px;
            margin: 0 auto;
            max-width: 330px;
            padding: 40px 40px;

        }
        #logo-container{
            margin: auto;
            margin-bottom: 10px;
            width:227px;
            height:74px;
            background-repeat: no-repeat;
            background-size: contain;
            background-image:url('{{ secure_asset('assets/img/hu-logo-medium.svg') }}');
        }
        .alert {
            color: #555;
            font-size: 13px;
            border-radius: 10px;
            padding: 10px 10px 10px 36px;
            margin: 10px;
        }
        .alert > span {
            font-weight:bold;
            text-transform:uppercase;
        }
        .alert-error {
            background:#ffecec url('/assets/img/error.png') no-repeat 10px 50%;
            border:1px solid #f5aca6;
        }
        .alert-success {
            background:#e9ffd9 url('/assets/img/success.png') no-repeat 10px 50%;
            border:1px solid #a6ca8a;
        }
        .control-label span.required:before {
            content: "*";
            padding-left: 5px;
            color: #ff0000;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <!-- Page Content -->
    <div id="page-content-wrapper">
        @yield('content')
    </div>
</div>
</body>
</html>

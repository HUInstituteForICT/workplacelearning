<?php
/**
 * This file (HUerror.blade.php) was created on 10/27/2016 at 02:53.
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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- Jquery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <title>@yield('httperrno') - {{ __('errors.occurred') }}</title>
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
            max-width: 500px;
            padding: 40px 40px;

        }
        #logo-container{
            margin: auto;
            margin-bottom: 10px;
            width:227px;
            height:74px;
            background-image:url('{{ secure_asset('assets/img/hu-logo-medium.svg') }}');
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container">
            <div class="row main">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <div id="logo-container"></div>
                    </div>
                </div>
                <div class="main-login main-center">
                    <h2>@yield('title')</h2>
                    <p>@yield('content')</p>
                    <a href="{{ '/' }}">{{ __('errors.returnhome') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


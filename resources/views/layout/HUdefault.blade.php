<?php
/**
 * This file (HUdefault.blade.php) was created on 04/23/2016 at 15:49.
 * Author: Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
<!doctype html>
<html>
<head>
    @include('includes.head')
    <title>@yield('title') - Werkplekleren</title>
</head>
<body>

<!-- Top Bar -->
<div id="menu-bar">
    @include('includes.header')
</div>
<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        @include('includes.sidebar')
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        @include('includes.notification')
        @yield('content')
    </div>

    <!-- /#wrapper -->
</div>
<div id="footer">
    <p>&copy; {{ date('Y') }} - HU University of Applied Sciences | Icons courtesy of <a href="http://famfamfam.com">FamFamFam.com</a></p>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.8/validator.min.js"></script>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>

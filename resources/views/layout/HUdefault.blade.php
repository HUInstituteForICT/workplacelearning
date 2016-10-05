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
        @yield('content')
    </div>

    <div id="footer">
        <script>
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
        </script>
        <!-- /#wrapper -->
    </div>
</div>
</body>
</html>

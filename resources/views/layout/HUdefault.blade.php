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
    <title>@yield('title') - {{ __('general.werkplekleren') }}</title>
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

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.8/validator.min.js"></script>
<script src="/messages.js"></script>
<script>
    Lang.setLocale('{{ App::getLocale() }}')
</script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>

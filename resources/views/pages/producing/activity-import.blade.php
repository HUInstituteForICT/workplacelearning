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

    @section('title')
        Admin dashboard
    @stop
    @section('content')
        <div class="container-fluid">
            <div class="panel-body">
                <hr/>
                @if (count($errors) > 0)
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Oeps!</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">{{ __('activity.import-error-message') }}</p>
                    </div>
                    <div>
                        @foreach($errors as $rowNr=>$valueArray)

                            @foreach($valueArray as $value)
                                <p class="alert alert-error">{{__('activity.row')}} {{$rowNr}}: {{$value[0]}}</p>
                            @endforeach
                        @endforeach
                    </div>
                @elseif(!empty($successMsg))
                    <div>
                        <div class="modal-header">
                            <h4 class="modal-title text-center">{{ __('activity.import-succes-header-message') }}</h4>
                        </div>
                        <div class="modal-body alert-success">
                            <p class="text-center">{{ __('activity.import-succes-message') }}</p>
                        </div>
                    </div>
                @endif
                {{dd()}}
            </div>
        </div>
</div>
</div>
</div>

@include('js.linking')
@stop

<!-- /#wrapper -->
</div>

<script>
    $("#menu-toggle").click(function (e) {
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



<?php
/**
 * This file (header.blade.php) was created on 04/23/2016 at 15:43.
 * Author: Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
    <a href="#menu-toggle"><img class="toggle" id="menu-toggle" src="{{ secure_asset('assets/img/menu-toggle.png') }}" /></a>
    <a class="nav-tile" href="{{ url('/') }}"><img class="logo" src="{{ secure_asset('assets/img/hu-logo.png') }}" /> <span>{{ __('elements.header.title') }}</span></a>
    @if(Auth::check())
    <a href="{{ url('/logout') }}"><img class="logout" src="{{ secure_asset('assets/img/btn-logout.svg') }}" /></a>
    <a href="{{ url('/profiel') }}"><img class="logout" src="{{ secure_asset('assets/img/btn-setting.svg') }}" /></a>
        <div class="stud-info-header">
        <p>{{ Auth::user()->lastname .", ". Auth::user()->getInitials() }}<br />
        @if(Auth::user()->isStudent())
            {{ (Auth::user()->hasCurrentWorkplaceLearningPeriod()) ? Auth::user()->getCurrentWorkplace()->wp_name ." (".__('general.until-header') ." ".date('d-m-Y', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->enddate)).")" : __('general.no-internship-active-header') }}
        @elseif(Auth::user()->isTeacher())
            <span class="label label-primary">Teacher</span>
        @elseif(Auth::user()->isAdmin())
            <span class="label label-primary">Admin</span>
        @endif
    </div>
    @endif

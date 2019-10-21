<!-- Sidebar -->
<div class="sidebar-nav">
    @if(Auth::user()->isStudent())
        @include('includes._sidebar_student')
        <div class="clearfix"></div>
        <br/>
    @endif

    @if(Auth::user()->isTeacher())
        @include('includes._sidebar_teacher')
        <div class="clearfix"></div>
        <br/>
    @endif

    @if(Auth::user()->isAdmin())
        @include('includes._sidebar_admin')
        <div class="clearfix"></div>
        <br/>
    @endif


    <div class="footer-tile">

        {{ __('general.found-bug') }} <a href="{{ route('bugreport') }}">{{ __('general.bug-tell-us') }}</a>
        <br/><br/>

        &copy; {{ date('Y') }} - HU University of Applied Sciences
        <br/> Icons courtesy of <a href="http://famfamfam.com">FamFamFam.com</a>
    </div>
</div>





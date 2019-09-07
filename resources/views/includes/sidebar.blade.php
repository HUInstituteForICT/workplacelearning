<!-- Sidebar -->
<div class="sidebar-nav">
    <a class="nav-tile" href="{{ '/home' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/nieuws_wit.svg', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.dash') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/process' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/productivity_blauw.png', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.input') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/analysis' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.reports') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/deadline' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/agenda_blauw.svg', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.calendar') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/profiel' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/BewijsInschrijving_wit.svg', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.profile') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/progress' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/Studievoortgang_blauw.svg', true) }}"/>
            <div class="nav-title">{{ __('elements.sidebar.labels.settings') }}</div>
        </div>
    </a>
    @if(Auth::user()->isTeacher())

        <a class="nav-tile" href="{{ route('tips-app') }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}"/>
                <div class="nav-title"
                     style="word-break: break-all">{{ __('elements.sidebar.labels.tips') }}</div>
            </div>
        </a>

        <a class="nav-tile" href="{{ '/education-programs' }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/nieuws_blauw.svg', true) }}"/>
                <div class="nav-title"
                     title="{{ __('elements.sidebar.labels.educationprograms') }}"
                     style="word-break: break-all">{{ __('elements.sidebar.labels.educationprograms') }}</div>
            </div>
        </a>

        <a class="nav-tile" href="{{ route('dashboard.index') }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/graph_wit.svg', true) }}"/>
                <div title="{{ __('elements.sidebar.labels.analytics_dashboard') }}" class="nav-title">{{ __('elements.sidebar.labels.analytics_dashboard') }}</div>
            </div>
        </a>

        <a class="nav-tile" href="{{ route('template.index') }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/agenda_blauw.svg', true) }}"/>
                <div title="{{ __('elements.sidebar.labels.template') }}" class="nav-title">{{ __('elements.sidebar.labels.template') }}</div>
            </div>
        </a>
    @endif

    @if(Auth::user()->isAdmin())
        <a class="nav-tile" href="{{ route('admin-dashboard') }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/BewijsInschrijving_wit.svg', true) }}"/>
                <div class="nav-title">Admin</div>
            </div>
        </a>
    @endif


</div>


<div class="footer-tile">

    {{ __('general.found-bug') }} <a href="{{ route('bugreport') }}">{{ __('general.bug-tell-us') }}</a>
    <br/><br/>

    &copy; {{ date('Y') }} - HU University of Applied Sciences
    <br/> Icons courtesy of <a href="http://famfamfam.com">FamFamFam.com</a>
</div>




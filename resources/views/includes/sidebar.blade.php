<!-- Sidebar -->
<div class="sidebar-nav">
    <a class="nav-tile" href="{{ '/home' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/nieuws_wit.svg', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.dash') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/process' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/productivity_blauw.png', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.input') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/analysis' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.reports') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/deadline' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/agenda_blauw.svg', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.calendar') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/profiel' }}">
        <div class="tile blue_tile">
            <img class="icon" src="{{ URL::asset('assets/img/BewijsInschrijving_wit.svg', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.profile') }}</div>
        </div>
    </a>
    <a class="nav-tile" href="{{ '/progress/1' }}">
        <div class="tile white_tile">
            <img class="icon" src="{{ URL::asset('assets/img/Studievoortgang_blauw.svg', true) }}"/>
            <div class="nav-title">{{ Lang::get('elements.sidebar.labels.settings') }}</div>
        </div>
    </a>

    @if(Auth::user()->getUserLevel() === 1)

        <a class="nav-tile" href="{{ route('dashboard.index') }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/graph_wit.svg', true) }}"/>
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.analytics_board') }}</div>
            </div>
        </a>

        <a class="nav-tile" href="{{ '/education-programs' }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/nieuws_blauw.svg', true) }}"/>
                <div class="nav-title"
                     style="word-break: break-all">{{ Lang::get('elements.sidebar.labels.educationprograms') }}</div>
            </div>
        </a>

        <a class="nav-tile" href="{{ route('template.index') }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/agenda_wit.svg', true) }}"/>
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.template') }}</div>
            </div>
        </a>
    @endif

</div>
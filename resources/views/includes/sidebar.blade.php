    <!-- Sidebar -->
    <div class="sidebar-nav">
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/home', array()) }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/nieuws_wit.svg', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.dash') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/process', array()) }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/productivity_blauw.png', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.input') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/analysis', array()) }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.reports') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/deadline', array()) }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/agenda_blauw.svg', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.calendar') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/profiel', array()) }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/BewijsInschrijving_wit.svg', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.profile') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, '/progress/1', array()) }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/Studievoortgang_blauw.svg', true) }}" />
                <div class="nav-title">{{ Lang::get('elements.sidebar.labels.settings') }}</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, route('dashboard.index'), array()) }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}" />
                <div class="nav-title">Analytics Dashboard</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, route('analytics-index'), array()) }}">
            <div class="tile white_tile">
                <img class="icon" src="{{ URL::asset('assets/img/Studievoortgang_blauw.svg', true) }}" />
                <div class="nav-title">Analytics</div>
            </div>
        </a>
        <a class="nav-tile" href="{{ LaravelLocalization::GetLocalizedURL(null, route('charts.index'), array()) }}">
            <div class="tile blue_tile">
                <img class="icon" src="{{ URL::asset('assets/img/cursus_wit.svg', true) }}" />
                <div class="nav-title">Analytics Charts</div>
            </div>
        </a>
    </div>
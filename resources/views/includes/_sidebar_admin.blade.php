<a class="nav-tile" href="{{ route('admin-dashboard') }}">
    <div class="tile white_tile">
        <img class="icon" src="{{ URL::asset('assets/img/BewijsInschrijving_blauw.svg', true) }}"/>
        <div class="nav-title">Admin</div>
    </div>
</a>

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
<a class="nav-tile" href="{{ route('admin-linking') }}">
    <div class="tile white_tile">
        <img class="icon" src="{{ URL::asset('assets/img/connect_blauw.svg', true) }}"/>
        <div class="nav-title">{{ __('elements.sidebar.labels.koppelen')  }}</div>
    </div>
</a>
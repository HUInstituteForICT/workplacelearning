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
<a class="nav-tile" href="{{ route('saved-learning-items') }}">
    <div class="tile blue_tile">
        <img class="icon" src="{{ URL::asset('assets/img/opgeslagen_icon_wit.svg', true) }}"/>
        <div class="nav-title">Opgeslagen</div>
    </div>
</a>
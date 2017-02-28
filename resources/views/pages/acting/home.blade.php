@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <h1>{{ Lang::get('dashboard.title') }}</h1>
                    <p>Welkom bij de Werkplekleren App van Hogeschool Utrecht. Met deze app krijg je inzicht in je leerproces tijdens het werken in de beroepspraktijk, bijvoorbeeld tijdens je stage. Met dit inzicht kun je nog meer uit je stage halen en jezelf optimaal ontwikkelen!
                        <br /><br />In het menu zie je een aantal tegels.</p>
                    <ul>
                        <li>Via de tegel <b>Leerproces</b> kun je je werkzaamheden invoeren.</li>
                        <li>Via de tegel <b>Voortgang</b> kun je alle ingevoerde leermomenten terugvinden. Uiterlijk in maart komt hier de mogelijkheid bij om leermomenten te exporteren per leervraag of competentie.</li>
                        <li>Via de tegel <b>Analyse</b> kun je door verschillende analyses inzichten krijgen over de leermomenten die je hebt doorgemaakt, over hoe en met wie je geleerd hebt. Ook krijg je persoonlijke tips over acties die je kunt ondernemen om moeilijkheden te overwinnen en nog meer uit je stage te halen.</li>
                        <li>Via de tegel <b>Deadlines</b> kun je in een handig overzicht belangrijke deadlines of afspraken bijhouden.</li>
                        <li>Via de tegel <b>Profiel</b> kun je je stages beheren.</li>
                    </ul>
                    <p>Veel succes en plezier tijdens jouw leren op de werkplek!</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <br /><a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> Heb je tips of vragen? Geef ze aan ons door!</a>
                </div>
            </div>
        </div>
@stop

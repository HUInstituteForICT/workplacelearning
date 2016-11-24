@extends('layout.HUdefault')
@section('title')
    Analyse
@stop
@section('content')

    <div class="container-fluid">

        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()->getCurrentInternshipPeriod() != null && Auth::user()->getCurrentInternshipPeriod()->hasLoggedHours())
            <div class="row">
                <div class="col-lg-6">
                    <h1>{{ Lang::get('rapportages.pageheader') }}</h1>
                    <p>Deze pagina geeft je meer inzicht in hoe je werkt en leert, en helpt je om na te denken over je werkzaamheden tijdens je stage, zodat je inzicht krijgt in hoe jij optimaal leert en zo je leerproces kunt verbeteren.</p>
                    <p>Kies hieronder voor een maand om de activiteiten in die maand te analyseren, of kies ervoor om alles te bekijken.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h3>Aantal geregistreerde uren</h3>
                </div>
                <div class="col-lg-1">
                    <p>Aantal uren: </p>
                </div>
                <div class="col-lg-4">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{ round(($numhours/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%">
                            @if($numhours >= (Auth::user()->getCurrentInternshipPeriod()->aantaluren / 2))
                                {{ $numhours." / ".Auth::user()->getCurrentInternshipPeriod()->aantaluren." uur (".round(($numhours/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%)
                            @endif
                        </div>
                        <div class="progress-bar" role="progressbar" style="width:{{ (100-round(($numhours/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1)) }}%">
                            @if($numhours < (Auth::user()->getCurrentInternshipPeriod()->aantaluren / 2))
                                {{ $numhours." / ".Auth::user()->getCurrentInternshipPeriod()->aantaluren." uur (".round(($numhours/Auth::user()->getCurrentInternshipPeriod()->aantaluren)*100,1) }}%)
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h3>Tijd per categorie</h3>
                    <canvas id="chart_hours"></canvas>
                    <script>
                        var canvasHours = document.getElementById('chart_hours');
                        var chart_hours = new Chart(canvasHours, {
                            type: 'pie',
                            data: {
                                labels: [ @foreach($analysis as $category) "{{ $category->name }}", @endforeach ],
                                datasets: [{
                                    data: [ @foreach($analysis as $category) "{{ round($category->totalhours / $numhours * 100) }}", @endforeach ],
                                    backgroundColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    hoverBackgroundColor: []
                                }]
                            },
                            options: {
                                tooltips: {
                                    enabled: true,
                                    mode: 'single',
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            var tooltipLabel = data.labels[tooltipItem.index];
                                            var tooltipData = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                            return tooltipLabel + ' ' + tooltipData + '%';
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </dittv>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>Kies een maand om weer te geven</h3>
                    <?php
                        $intlfmt = new IntlDateFormatter(
                                (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                                IntlDateFormatter::GREGORIAN,
                                IntlDateFormatter::NONE,
                                NULL,
                                NULL,
                                "MMMM YYYY"
                        );
                        $begin  = strtotime((new DateTime(Auth::user()->getCurrentInternshipPeriod()->startdatum))->modify("first day of this month")->format('Y-m-d'));
                        $end    = strtotime((new DateTime(Auth::user()->getCurrentInternshipPeriod()->einddatum))->modify("last day of this month")->format('Y-m-d'));
                    ?>
                    <a href="{{ LaravelLocalization::GetLocalizedURL(null, '/analyse/all/all', array()) }}">Toon alle gegevens</a><br />
                    @while($end > $begin)
                        @if($end <= strtotime((new DateTime("now"))->modify("last day of this month")->format('Y-m-d')))
                            <a href="{{ LaravelLocalization::GetLocalizedURL(null, '/analyse/'.date('Y', $end).'/'.date('m', $end), array()) }}">{{ ucwords($intlfmt->format($end)) }}</a><br />
                        @endif
                        <?php $end = strtotime("-1 month", $end); ?>
                    @endwhile
                </div>
            </div>

        @endif

    </div>

@stop

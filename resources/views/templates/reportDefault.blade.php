<link type="text/css" rel="stylesheet" href="{{ URL::asset('public/css/PDFstyle.css') }}">
<div id="hu-logo"><img src="{{ URL::asset('public/assets/img/hu-logo-medium.svg') }}" /></div>
<div id="info-block">
    <b>{{ __('rapportages.studentnumber') }}:</b> {{ $data['student']['studentnummer'] }}<br />
    <b>{{ __('rapportages.studentname') }}:</b> {{ $data['student']['initialen'] ." ". $data['student']['achternaam'] ." (". $data['student']['voornaam'] .")" }}<br /><br />
    <b>{{ __('rapportages.companyname') }}: </b><br />
    <b>{{ __('rapportages.internshipperiod') }}: </b>{{ date("d-m-Y", strtotime($data['internship']['startDate'])) ." ". __("rapportages.uptoincluding") ." ". date("d-m-Y", strtotime($data['internship']['endDate'])) }}<br />
    <b>{{ __('rapportages.reportperiod') }}: </b>{{ date("d-m-Y", strtotime($data['report']['startDate'])) ." ". __("rapportages.uptoincluding") ." ". date("d-m-Y", strtotime($data['report']['endDate'])) }}
</div>
<div class="page-content">
</div>
<div class="page-break"></div>
<!-- Page 2 -->
<div class="page-content">
    <h3>Detail Urenverdeling</h3>
    <p>Deze pagina geeft weer per maand hoe je je tijd besteed hebt. Bespreek dit met je stagebegeleider tijdens evaluatiegesprekken en vergelijk het met medestudenten om tot een beter inzicht te komen.</p>
    @if(!empty($data['taskChartPerMonth']))
        <div class="chartcontainer">
        @foreach($data['taskChartPerMonth'] as $monthno => $url)
            <img class="2-chart" src="{{ $url }}&ext=.png" />
        @endforeach
        </div>
    @endif
</div>
<div class="page-break"></div>
<!-- Page 3 -->
<div class="page-content">
    <div class="content">
        <h3>Weekstaten</h3>
        <table>
            <thead>
                <tr>
                    <td>Datum</td>
                    <td>Omschrijving</td>
                    <td>Tijd (Uren)</td>
                    <td>Samenwerkingsverband</td>
                    <td>Complexiteit</td>
                </tr>
            </thead>
            @foreach($data['werkzaamheden'] as $wzh)
                <tr>
                    <td>{{ date('m-d', strtotime($wzh['date'])) }}</td>
                    <td>{{ $wzh['description'] }}</td>
                    <td>{{ $wzh['hours'] ." ". Lang::choice('dashboard.hours', $wzh['hours']) }}</td>
                    <td>{{ $wzh['cooperation'] }}</td>
                    <td>{{ $wzh['difficulty'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<div class="page-break"></div>


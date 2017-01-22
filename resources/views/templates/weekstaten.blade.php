<link type="text/css" rel="stylesheet" href="{{ URL::asset('css/weekstaten.css') }}">
<!-- Front Page -->
<head>
    <title>{{
        $student->getInitials()
        ." ".$student->achternaam
        ." @ ".$stage->bedrijfsnaam
        ." (".$stageperiode->startdatum
        ." t/m ".$stageperiode->einddatum.")"
    }}</title>
</head>
<body>
    <div class="page-container">
        <table class="full-width" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <td colspan="2">In te vullen door de stagiair</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Naam Stagiair: {{ $student->voornaam." ".$student->achternaam  }}</td>
                <td>Stageverlenende Organisatie: {{ $stage->bedrijfsnaam }}
                    <br /><br />Naam Bedrijfsbegeleider: {{ $stage->contactpersoon }}<br /><br /><br />
                </td>
            </tr>
            <tr>
                <td rowspan="2">Studentnummer: {{ $student->studentnummer }}</td>
                <td>Adres: </td>
            </tr>
            <tr>
                <td>Postcode & Plaats: {{ $stage->postcode.", ".$stage->plaats }}</td>
            </tr>
            <tr>
                <td>Totaal aantal dagen stage gelopen:</td>
                <td>Stagedocent: </td>
            </tr>
            </tbody>
        </table>
        <table class="full-width" cellpadding="0" cellspacing="0" style="margin-top: 50px;">
            <thead>
            <tr>
                <td colspan="2">In te vullen door het bedrijf</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 280px;">Bevestiging, namens het bedrijf, dat het aantal dagen dat stage is gelopen, hierboven naar waarheid is ingevuld.</td>
                <td>Naam: {{ $stage->contactpersoon }}
                    <br /><br /><br />Datum: {{ date('d-m-Y') }}
                    <br /><br /><br />Handtekening:
                    <br /><br /><br />
                </td>
            </tr>
            <tr>
                <td colspan="2">Opmerkingen van de stageverlendende organisatie:</td>
            </tr>
            </tbody>
        </table>
        <div class="page-break"></div>

        <!-- Auto Import Begin -->
        @while(strtotime($date_loop) < strtotime($stageperiode->einddatum) && strtotime($date_loop) < time())
        <?php $weekno = 1; ?>
        <table class="full-width" cellpadding="0" cellspacing="0" style="margin-top: 50px;">
            <thead>
            <tr>
                <td>Dag</td>
                <td>Datum</td>
                <td>Werkzaamheden</td>
            </tr>
            </thead>
            <tbody>
            <?php $days_this_week = 0; ?>
                @for($i=1; $i<=5; $i++)
                    <tr>
                        <td style="width:100px;">{{ ucwords($datefmt->format(strtotime($date_loop))) }}</td>
                        <td style="width:100px;">{{ $date_loop }}</td>
                        <td>
                            <?php $hrs = 0; ?>
                            @if(array_key_exists("".date('d-m-Y', strtotime($date_loop)), $wzh_array))
                            @foreach($wzh_array["".date('d-m-Y', strtotime($date_loop))] as $wzh)
                                <?php $hrs += $wzh['hours']; ?>
                                - {{ $wzh['description'] }}<br />
                            @endforeach
                            @else
                                {{ "Absent" }}
                            @endif
                        </td>
                    </tr>
                    <?php
                    $days_this_week += ($hrs > 7.5) ? 1 : 0;
                    $date_loop = date('d-m-Y', strtotime("+1 day", strtotime($date_loop)));
                    ?>
                @endfor
                <tr>
                    <td colspan="2">Aantal Dagen Gewerkt (7,5 uur of meer):</td>
                    <td>{{ $days_this_week . (($days_this_week == 1) ? " dag" : " dagen") }}</td>
                </tr>
                <tr>
                    <td colspan="2">Reden(en) Eventuele Absentie:</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3">Opmerkingen:</td>
                </tr>
                <?php
                $date_loop = date('d-m-Y', strtotime("+2 days", strtotime($date_loop))); ?>
            </tbody>
        </table>
        <div class="page-break"></div>
        @endwhile
    </div>
</body>

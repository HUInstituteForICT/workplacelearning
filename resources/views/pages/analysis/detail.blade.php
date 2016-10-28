@extends('layout.HUdefault')
@section('title')
    Detail Page
@stop
@section('content')

    <div class="container-fluid">
        <script>
            $(document).ready(function(){
                $(".expand-detail").click(function(e){
                    $("#detail-"+($(this).attr("data-id"))).toggle();
                    e.preventDefault();
                });
            });
        </script>

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
                <div class="col-lg-12">
                    <h1>{{ Lang::get('rapportages.pageheader') }}
                        <?php
                        $intlfmt = new IntlDateFormatter(
                                (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                                IntlDateFormatter::GREGORIAN,
                                IntlDateFormatter::NONE,
                                NULL,
                                NULL,
                                "MMMM YYYY"
                        );
                        echo $intlfmt->format(strtotime("2016-".$monthno."-01"));
                        ?>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal')) !!}
                    <h2>Statistiek</h2>
                    <div class="form-group">
                        {!! Form::label('', "Gemiddelde Moeilijkheid", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p class="form-control-static">{{ round($analysis['avg_difficulty'],1) }} (10 is het meest complex)</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', "Percentage moeilijke activiteiten", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p class="form-control-static">{{ round(($analysis['num_difficult_wzh']/$analysis['num_wzh'])*100,1) }}% van je werkzaamheden vond je <b>Moeilijk</b></p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', "Percentage zelfstandig werken", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p class="form-control-static">{{ round(($analysis['num_hours_alone']/$analysis['num_hours'])*100,1) }}% van de activiteiten voerde je Alleen uit</p></div>
                    </div>

                    @if((($analysis['num_hours_alone']/$analysis['num_hours'])*100) > 75 && (($analysis['num_difficult_wzh']/$analysis['num_wzh'])*100) > 50)
                        <p>Tip: Je hebt {{ round(($analysis['num_hours_alone']/$analysis['num_hours'])*100,1) }}% van de tijd Alleen gewerkt, en je vond {{ round(($analysis['num_difficult_wzh']/$analysis['num_wzh'])*100,1) }}% van dit zelfstandige werk Moeilijk. Je zou met je bedrijfsbegeleider kunnen bespreken op welke manier je er samen voor kunt zorgen dat je eerder hulp of ondersteuning krijgt bij moeilijke werkzaamheden.</p>
                    @endif
                    {!! Form::close() !!}
                    <canvas id="chart_categories"></canvas>

                    <script>
                        var canvas_categories = document.getElementById("chart_categories");
                        var cat_chart = new Chart(canvas_categories, {
                            type: 'bar',
                            data: {
                                labels: [ @foreach($analysis['category_difficulty'] as $category) "{{ $category->name }}", @endforeach ],
                                datasets: [{
                                    label: 'Moeilijkheidsgraad op schaal van 1-10',
                                    data: [
                                        @foreach($analysis['category_difficulty'] as $category)
                                                "{{ $category->difficulty }}",
                                        @endforeach
                                    ],
                                    backgroundColor: [
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                    <hr />
                </div>
            </div>

            <!-- Tips -->
            <div class="row">
                <div class="col-md-12">
                    <h2>Tips</h2>
                </div>
            </div>

            @if(count($chains) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h2>Detail</h2>
                        <p>Hieronder zie je alle series van opeenvolgende activiteiten in deze maand.</p>
                        <p>Je kunt hier op terugblikken en bekijken wat je in deze maand moeilijk vond en hoe je moeilijke situaties hebt overwonnen. Als je deze informatie wilt delen, zou je het kunnen bespreken bij een voortgangsgesprek met je bedrijfsbegeleider of je stagedocent.</p>
                    </div>
                    <table class="table blockTable col-md-12">
                        <thead class="blue_tile">
                        <tr>
                            <td>Datum</td>
                            <td>Activiteit</td>
                            <td>Aantal Uren</td>
                            <td>Afgerond?</td>
                            <td>Toon Detail</td>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($chains as $wzh_chain)
                            <tr>
                                <td>{{
                                    date('d-m', strtotime(reset($wzh_chain)->wzh_datum))
                                    ." t/m ".
                                    date('d-m', strtotime(end($wzh_chain)->wzh_datum))
                                }}</td>
                                <td>{{
                                    reset($wzh_chain)->wzh_omschrijving
                                    ." - ".
                                    end($wzh_chain)->wzh_omschrijving
                                }}</td>
                                <td><?php
                                    $hrs = 0;
                                    foreach($wzh_chain as $w){ $hrs += $w->wzh_aantaluren;}
                                ?>{{ $hrs }}
                                </td>
                                <td>{{ end($wzh_chain)->getStatus() }}</td>
                                <td><a data-id="{{ reset($wzh_chain)->wzh_id }}" href="#" class="expand-detail">Toon Detail</a></td>
                            </tr>
                            <tr id="detail-{{ reset($wzh_chain)->wzh_id }}" style="display:none;" >
                            <td colspan="5">
                            <table class="table blockTable col-md-12">
                                <tbody>
                                <tr class="blue_tile">
                                    <td>Datum</td>
                                    <td>Omschrijving</td>
                                    <td>Complexiteit</td>
                                    <td>Tijd besteed</td>
                                    <td>Hulpbron</td>
                                    <td>Feedback</td>
                                    <td>Feedforward</td>
                                </tr>
                                @foreach($wzh_chain as $wzh)
                                    <?php
                                    $fb = $wzh->getFeedback()
                                    ?>
                                    <tr>
                                        <td>{{ date('d-m', strtotime($wzh->wzh_datum)) }}</td>
                                        <td>{{ $wzh->wzh_omschrijving }}</td>
                                        <td>{{ ($fb != null) ? $wzh->getMoeilijkheid().": ".$fb->notfinished : $wzh->getMoeilijkheid() }}</td>
                                        <td>{{ $wzh->getAantalUrenString() }}</td>
                                        <td>{{
                                    (($fb != null && $wzh->lerenmet == "persoon") ? ($fb->help_asked == 1) ? "Hulp gekregen: " : "Hulp gevraagd: " : "")
                                    .ucwords($wzh->lerenmet)
                                    .(($wzh->lerenmetdetail != null) ? ", ".$wzh->getlerenmetdetail() : "")
                                    }}
                                        </td>
                                        <td>{!! ($fb != null) ? "Je was " . (($fb->progress_satisfied == 2) ? "tevreden" : "niet tevreden") . " met het verloop van deze activiteit (<a href='".url('feedback/'.$fb->fb_id)."'>Detail</a>)." : "" !!}</td>
                                        <td>{{ ($fb != null) ? $fb->vervolgstap_zelf : "" }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        @endif

    </div>

@stop
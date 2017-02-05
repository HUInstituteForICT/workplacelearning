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

        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
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
                        echo $intlfmt->format(strtotime($year."-".$monthno."-01"));
                        ?>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2>Tijd per categorie</h2>
                    <canvas id="chart_hours"></canvas>
                    <script>
                        var canvasHours = document.getElementById('chart_hours');
                        var chart_hours = new Chart(canvasHours, {
                            type: 'pie',
                            data: {
                                labels: [ @foreach($analysis['num_hours_category'] as $category) "{{ $category->name }}", @endforeach ],
                                datasets: [{
                                    data: [ @foreach($analysis['num_hours_category'] as $category) "{{ round($category->totalhours / $analysis['num_hours'] * 100) }}", @endforeach ],
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
                        <div class="col-sm-9"><p class="form-control-static">{{ round(($analysis['hours_difficult_lap']/$analysis['num_hours'])*100,1) }}% van je werkzaamheden vond je <b>Moeilijk</b></p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', "Percentage zelfstandig werken", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p class="form-control-static">{{ round(($analysis['num_hours_alone']/$analysis['num_hours'])*100,1) }}% van de activiteiten voerde je Alleen uit</p></div>
                    </div>

                    @if((($analysis['num_hours_alone']/$analysis['num_hours'])*100) > 75 && (($analysis['num_difficult_lap']/$analysis['num_lap'])*100) > 50)
                        <p>Tip: Je hebt {{ round(($analysis['num_hours_alone']/$analysis['num_hours'])*100,1) }}% van de tijd Alleen gewerkt, en je vond {{ round(($analysis['num_difficult_lap']/$analysis['num_lap'])*100,1) }}% van dit zelfstandige werk Moeilijk. Je zou met je bedrijfsbegeleider kunnen bespreken op welke manier je er samen voor kunt zorgen dat je eerder hulp of ondersteuning krijgt bij moeilijke werkzaamheden.</p>
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
                        @foreach($chains as $lap_chain)
                            <tr class="{{ (array_search($lap_chain,$chains) % 2 == 0) ? "even" : "odd" }}-row">
                                <td>{{ date('d-m', strtotime(reset($lap_chain)->date)) }}
                                    @if(reset($lap_chain) != end($lap_chain))
                                        {{  " t/m ".date('d-m', strtotime(end($lap_chain)->date)) }}
                                    @endif
                                </td>
                                <td>{{ reset($lap_chain)->description }}
                                    @if(reset($lap_chain) != end($lap_chain))
                                        {{ " - ".end($lap_chain)->description }}
                                    @endif
                                </td>
                                <td><?php
                                    $hrs = 0;
                                    foreach($lap_chain as $w){ $hrs += $w->duration;}
                                ?>{{ $hrs }}
                                </td>
                                <td>{{ end($lap_chain)->getStatus() }}</td>
                                <td>
                                    @if(reset($lap_chain) != end($lap_chain))
                                        <a data-id="{{ reset($lap_chain)->lap_id }}" href="#" class="expand-detail">Toon Detail</a>
                                    @else
                                        <p>N.V.T.</p>
                                    @endif
                                </td>
                            </tr>
                            @if(count($lap_chain) > 1)
                            <tr class="odd-row" id="detail-{{ reset($lap_chain)->lap_id }}" style="display:none;" >
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
                                @foreach($lap_chain as $lap)
                                    <?php
                                    $fb = $lap->getFeedback()
                                    ?>
                                    <tr>
                                        <td>{{ date('d-m', strtotime($lap->date)) }}</td>
                                        <td>{{ $lap->description }}</td>
                                        <td>{{ ($fb != null) ? $lap->getDifficulty().": ".$fb->notfinished : $lap->getDifficulty() }}</td>
                                        <td>{{ $lap->getDurationString() }}</td>
                                        <td>{{ $lap->getResourceDetail() }}</td>
                                        <td>{!! ($fb != null) ? "Je was " . (($fb->progress_satisfied == 2) ? "tevreden" : "niet tevreden") . " met het verloop van deze activiteit (<a href='".URL::to("feedback-producing", array("id" => $fb->fb_id))."'>Detail</a>)." : "" !!}</td>
                                        <td>{{ ($fb != null) ? $fb->nextstep_self : "" }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        @endif

    </div>

@stop

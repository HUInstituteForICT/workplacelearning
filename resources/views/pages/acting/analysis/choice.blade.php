@extends('layout.HUdefault')
@section('title')
    Analyse
@stop
@section('content')
    <script>
        let lastColorIndex = 0;

        function getChartColor() {
            const colors = [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
            ];
            if (lastColorIndex === colors.length) {
                lastColorIndex = 0;
            }
            return colors[lastColorIndex++];
        }
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h1>Analyse</h1>
                <p>{{ trans('analysis.acting.description') }}</p>


                <p>Als je een tip hebt voor een analyse die je hier graag zou willen zien, geef dit dan aan ons door via
                    <a href="{{ route('bugreport') }}">deze pagina</a>.</p>

                <h3>Grafiek categorieën</h3>
                <canvas id="chart_timeslots"></canvas>
                <script>
                    var canvas_timeslots = document.getElementById("chart_timeslots");
                    var timeslots_chart = new Chart(canvas_timeslots, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('timeslot')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per categorie',
                                data: {!! $actingAnalysis->charts('timeslot')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    @foreach($actingAnalysis->charts('timeslot')->labels as $label)
                                    {{ "getChartColor(),"}}
                                    @endforeach

                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Grafiek leerdoelen</h3>
                <canvas id="chart_learninggoals"></canvas>
                <script>
                    var canvas_learninggoals = document.getElementById("chart_learninggoals");
                    var chart_learninggoals = new Chart(canvas_learninggoals, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('learninggoal')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per leerdoel',
                                data: {!! $actingAnalysis->charts('learninggoal')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    @foreach($actingAnalysis->charts('learninggoal')->labels as $label)
                                    {{ "getChartColor(),"}}
                                    @endforeach
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Grafiek competenties</h3>
                <canvas id="chart_competencies"></canvas>
                <script>
                    var canvas_competencies = document.getElementById("chart_competencies");
                    var chart_competencies = new Chart(canvas_competencies, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('competence')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per competentie',
                                data: {!! $actingAnalysis->charts('competence')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    @foreach($actingAnalysis->charts('competence')->labels as $label)
                                    {{ "getChartColor(),"}}
                                    @endforeach
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Grafiek personen</h3>
                <canvas id="chart_persons"></canvas>
                <script>
                    var canvas_persons = document.getElementById('chart_persons');
                    var chart_persons = new Chart(canvas_persons, {
                        type: 'pie',
                        data: {
                            labels: {!! $actingAnalysis->charts('person')->labels->toJson() !!},
                            datasets: [{
                                data: {!! $actingAnalysis->charts('person')->data->toJson() !!},
                                backgroundColor: [
                                    @foreach($actingAnalysis->charts('person')->labels as $label)
                                    {{ "getChartColor(),"}}
                                    @endforeach
                                ],
                                hoverBackgroundColor: []
                            }]
                        },
                        options: {
                            tooltips: {
                                enabled: true,
                                mode: 'single',
                                callbacks: {
                                    label: function (tooltipItem, data) {
                                        var tooltipLabel = data.labels[tooltipItem.index];
                                        var tooltipData = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                        return tooltipLabel + ' ' + tooltipData + '%';
                                    }
                                }
                            }
                        }
                    });
                </script>

                <br/><br/>

                @if(
                    $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage > 0 ||
                    $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage > 0 ||
                    $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage > 0
                )
                    <h3>Meest voorkomende combinaties</h3>

                    @if($actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage > 0)
                        <strong>Categorie & leervraag:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->timeslot->timeslot_text }}
                        met
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->learningGoal->learninggoal_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage }}% van je
                        activiteiten zijn met deze combinatie

                        <br/><br/>
                    @endif

                    @if($actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage > 0)
                        <strong>Categorie & competentie:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->timeslot->timeslot_text }}
                        met
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->competence->competence_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage }}% van je
                        activiteiten zijn met deze combinatie

                        <br/><br/>
                    @endif


                    @if($actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage > 0)
                        <strong>Leerdoel & competentie:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->learningGoal->learninggoal_label }}
                        met
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->competence->competence_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage }}% van
                        je
                        activiteiten zijn met deze combinatie

                        <br/><br/>
                    @endif
                @endif


                <h3>Tips</h3>
                <?php $tipCounter = 1; ?>

                {{--Percentage leermomenten bij leervraag zonder theorie--}}

                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals() as $learningGoal)



                    @if($actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) > 50)
                        <strong>Tip {{ $tipCounter }}</strong>: <br/>
                        Bij {{ $actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) }}%
                        van de leermomenten behorende bij {{ $learningGoal->learninggoal_label }}
                        gebruik je geen theorie. Misschien weet je niet goed welke theorie je hier bij zou
                        kunnen
                        gebruiken? Je zou hier ondersteuning bij kunnen vragen aan je begeleider vanuit de HU of
                        je
                        werkplek.
                        <br/><br/>

                        <?php $tipCounter++ ?>
                    @endif


                @endforeach

                {{--Percentage leermomenten in tijdslot hoger dan 30% tip--}}
                @foreach(Auth::user()->currentCohort()->timeslots()->get()->merge(Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()) as $timeslot)
                    @if($actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) >= 30)
                        <strong>Tip {{ $tipCounter }}</strong>: <br/>
                        {{ $actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) }}% van jouw
                        leermomenten vallen in de categorie {{ $timeslot->timeslot_text }}. Dit is blijkbaar
                        een
                        categorie waarin veel van jou gevraagd wordt. Bespreek dit eens met je
                        begeleiders
                        of een medestudent om samen na te gaan hoe dit komt.
                        <br/><br/>
                        <?php $tipCounter++ ?>
                    @endif

                @endforeach
            </div>
        </div>
    </div>

@stop

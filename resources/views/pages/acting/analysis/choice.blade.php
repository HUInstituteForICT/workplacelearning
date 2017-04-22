@extends('layout.HUdefault')
@section('title')
    Analyse
@stop
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h1>Analyse</h1>
                <p>Op deze pagina kun je binnenkort je ingevulde leermomenten analyseren.</p>
                <p>Je kunt bijvoorbeeld kijken in welk lesuur je de meeste leermomenten hebt ervaren. Of met welke
                    theorie of persoon je veel werkt en leert. Ook kun je bekijken hoe de verhouding is tussen geplande
                    en ongeplande leermomenten. Tenslotte wordt het mogelijk om inzicht te krijgen in de voortgang die
                    je boekt met het werken aan je leervragen.</p>
                <p>Als je een tip hebt voor een analyse die je hier graag zou willen zien, geef dit dan aan ons door via
                    <a href="{{ route('bugreport') }}">deze pagina</a>.</p>


                <h3>Chart timeslots</h3>
                <canvas id="chart_timeslots"></canvas>
                <script>
                    var canvas_timeslots = document.getElementById("chart_timeslots");
                    var timeslots_chart = new Chart(canvas_timeslots, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('timeslot')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per timeslot',
                                data: {!! $actingAnalysis->charts('timeslot')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
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
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Chart leerdoelen</h3>
                <canvas id="chart_learninggoals"></canvas>
                <script>
                    var canvas_learninggoals = document.getElementById("chart_learninggoals");
                    var chart_learninggoals = new Chart(canvas_learninggoals, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('learninggoal')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per leer doel',
                                data: {!! $actingAnalysis->charts('learninggoal')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
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
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Chart competenties</h3>
                <canvas id="chart_competencies"></canvas>
                <script>
                    var canvas_competencies = document.getElementById("chart_competencies");
                    var chart_competencies = new Chart(canvas_competencies, {
                        type: 'bar',
                        data: {
                            labels: {!! $actingAnalysis->charts('competence')->labels->toJson() !!},
                            datasets: [{
                                label: 'Percentage leermomenten per leer doel',
                                data: {!! $actingAnalysis->charts('competence')->data->toJson() !!},
                                backgroundColor: [],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
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
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <h3>Chart personen</h3>
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

                <h3>Chart theorie</h3>
                <canvas id="chart_materials"></canvas>
                <script>
                    var canvas_materials = document.getElementById('chart_materials');
                    var chart_materials = new Chart(canvas_materials, {
                        type: 'pie',
                        data: {
                            labels: {!! $actingAnalysis->charts('material')->labels->toJson() !!},
                            datasets: [{
                                data: {!! $actingAnalysis->charts('material')->data->toJson() !!},
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


                <strong>Meest voorkomende combinatie tijdslot & leervraag:</strong>
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->timeslot->timeslot_text }}
                met
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->learningGoal->learninggoal_label }}
                ,
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage }}% van je
                activiteiten zijn met deze combinatie

                <br/><br/>

                <strong>Meest voorkomende combinatie tijdslot & competentie:</strong>
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->timeslot->timeslot_text }} met
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->competence->competence_label }}
                ,
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage }}% van je
                activiteiten zijn met deze combinatie

                <br/><br/>

                <strong>Meest voorkomende combinatie leerdoel & competentie:</strong>
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->learningGoal->learninggoal_label }}
                met
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->competence->competence_label }}
                ,
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage }}% van je
                activiteiten zijn met deze combinatie

                <br/><br/>


                <h3>Percentage leermomenten bij leervraag zonder theorie</h3>
                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals() as $learningGoal)
                    <div>

                        <strong>Leervraag:</strong> {{ $learningGoal->learninggoal_label }}<br/>
                        <strong>Percentage:</strong> {{ $actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) }}
                        %<br/>

                        @if($actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) > 50)

                            Bij {{ $actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) }}
                            % van de leermomenten behorende bij {{ $learningGoal->learninggoal_label }}
                            gebruik je geen theorie. Misschien weet je niet goed welke theorie je hier bij zou kunnen
                            gebruiken? Je zou hier ondersteuning bij kunnen vragen aan je begeleider vanuit de HU of je
                            werkplek.

                        @endif
                        <br/><br/>
                    </div>


                @endforeach

                <h3>Percentage leermomenten in tijdslot hoger dan 30% tip</h3>
                @foreach(Auth::user()->getEducationProgram()->getTimeslots() as $timeslot)
                    <div>

                        <strong>Tijdslot:</strong> {{ $timeslot->timeslot_text }}<br/>
                        <strong>Percentage:</strong> {{ $actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) }}
                        %<br/>

                        @if($actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) > 30)
                            {{ $actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) }}% van jouw
                            leermomenten vinden plaats in tijdslot {{ $timeslot->timeslot_text }}. Dit is blijkbaar een
                            moment van de dag waarop veel van jou gevraagd wordt. Bespreek dit eens met je begeleiders
                            of een medestudent om samen na te gaan hoe dit komt.
                        @endif
                        <br/><br/>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@stop

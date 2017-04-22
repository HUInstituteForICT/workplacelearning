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
                <p>Je kunt bijvoorbeeld kijken in welk lesuur je de meeste leermomenten hebt ervaren. Of met welke theorie of persoon je veel werkt en leert. Ook kun je bekijken hoe de verhouding is tussen geplande en ongeplande leermomenten. Tenslotte wordt het mogelijk om inzicht te krijgen in de voortgang die je boekt met het werken aan je leervragen.</p>
                <p>Als je een tip hebt voor een analyse die je hier graag zou willen zien, geef dit dan aan ons door via <a href="{{ route('bugreport') }}">deze pagina</a>.</p>


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
                                backgroundColor: [
                                ],
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
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });
                </script>

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
                                backgroundColor: [
                                ],
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
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });
                </script>

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
                                backgroundColor: [
                                ],
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
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });
                </script>

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


                Meest voorkomende combinatie tijdslot & leervraag:
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->timeslot->timeslot_text }} met
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->learningGoal->learninggoal_label }},
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage }}% van je activiteiten zijn met deze combinatie

                <br/><br/>

                Meest voorkomende combinatie tijdslot & competentie:
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->timeslot->timeslot_text }} met
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->competence->competence_label }},
                {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage }}% van je activiteiten zijn met deze combinatie

                <br/><br/>

                Meest voorkomende combinatie leerdoel & competentie:
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->learningGoal->learninggoal_label }} met
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->competence->competence_label }},
                {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage }}% van je activiteiten zijn met deze combinatie

                <br/><br/>


                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals() as $learningGoal)
                    @if($actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) > 50)
                        Boven 50 %, gebruik theorie?
                    @endif
                    {{ $actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) }}
                @endforeach

            </div>
        </div>
    </div>
@stop

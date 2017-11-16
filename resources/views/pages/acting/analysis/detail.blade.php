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
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <p>{{ trans('analysis.acting.description') }}</p>


                <p>{{ Lang::get('general.tip_request') }}
                    <a href="{{ route('bugreport') }}">{{ Lang::get('general.this_page') }}</a>.</p>

                <h3>{{ Lang::get('analysis.graphs.categories') }}</h3>
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

                <h3>{{ Lang::get('analysis.graphs.learninggoals') }}</h3>
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

                <h3>{{ Lang::get('analysis.graphs.competencies') }}</h3>
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

                <h3>{{ Lang::get('analysis.graphs.persons') }}</h3>
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
                    <h3>{{ Lang::get('analysis.statistics.most_occurring_combo') }}</h3>

                    @if($actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage > 0)
                        <strong>{{ Lang::get('analysis.category-learninggoal') }}:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->timeslot->timeslot_text }}
                        {{ Lang::get('general.with') }}
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->learningGoal->learninggoal_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotLearningGoal')->percentage }}
                        {{ Lang::get('analysis.activities-with-this-combo') }}

                        <br/><br/>
                    @endif

                    @if($actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage > 0)
                        <strong>{{ Lang::get('analysis.category-competence') }}:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->timeslot->timeslot_text }}
                        {{ Lang::get('with') }}
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->competence->competence_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationTimeslotCompetence')->percentage }}
                        {{ Lang::get('analysis.activities-with-this-combo') }}

                        <br/><br/>
                    @endif


                    @if($actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage > 0)
                        <strong>{{ Lang::get('analysis.learninggoal-competence') }}:</strong>
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->learningGoal->learninggoal_label }}
                        {{ Lang::get('with') }}
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->competence->competence_label }}
                        ,
                        {{ $actingAnalysis->statistic('mostOftenCombinationLearningGoalCompetence')->percentage }}
                        {{ Lang::get('analysis.activities-with-this-combo') }}

                        <br/><br/>
                    @endif
                @endif


                <h3>Tips</h3>
                <?php $tipCounter = 1; ?>

                {{--Percentage leermomenten bij leervraag zonder theorie--}}

                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals() as $learningGoal)

                    @if($actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal) > 50)
                        <strong>{{ Lang::get('analysis.tip') }} {{ $tipCounter }}</strong>: <br/>

                        {{ Lang::get('analysis.tips.learninggoal-material', ["percentage" => $actingAnalysis->statistic('percentageLearningGoalWithoutMaterial', $learningGoal), "label" => $learningGoal->learninggoal_label]) }}
                        <br/><br/>

                        <?php $tipCounter++ ?>
                    @endif


                @endforeach

                {{--Percentage leermomenten in tijdslot hoger dan 30% tip--}}
                @foreach(Auth::user()->currentCohort()->timeslots()->get()->merge(Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()) as $timeslot)
                    @if($actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot) >= 30)
                        <strong>{{ Lang::get('analysis.tip') }} {{ $tipCounter }}</strong>: <br/>

                        {{ Lang::get('analysis.tips.activities-timeslot', ["percentage" => $actingAnalysis->statistic('percentageActivitiesInTimeslot', $timeslot), "label" => $timeslot->timeslot_text]) }}
                        <br/><br/>
                        <?php $tipCounter++ ?>
                    @endif

                @endforeach
            </div>
        </div>
    </div>

@stop

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


                @if($tips->count() > 0)
                    <h3>Tips</h3>
                @endif
                <?php $tipCounter = 1; ?>

                @foreach($tips as $tip)
                    @if($tip->likes->count() === 0 || $tip->likes[0]->type === 1)
                        <strong>{{ trans('analysis.tip') }} {{ $tipCounter }}</strong>
                        <div class="row">
                            @if($tip->likes->count() === 0)
                                <div class="col-md-1"
                                     style="display: inline-block; vertical-align: middle;   float: none;">

                                    <h2 class="h2" style="cursor: pointer;color: #00A1E2;" id="likeTip-{{ $tip->id }}"
                                        onclick="likeTip({{ $tip->id }}, 1)"
                                        target="_blank"><span class="glyphicon glyphicon-thumbs-up"/></h2>
                                    <h3 class="h3" style="cursor: pointer;color: #00A1E2;" id="likeTip-{{ $tip->id }}"
                                        onclick="likeTip({{ $tip->id }}, -1)"
                                        target="_blank"><span class="glyphicon glyphicon-thumbs-down"/></h3>
                                </div>@endif<!-- {{-- this html comment is a hack, allows vertical aligment ¯\_(ツ)_/¯ --}}
                                --><div class="col-md-11"
                                 style="display: inline-block; vertical-align: middle;   float: none;">
                                <p>{!! nl2br($tip->getTipText()) !!}</p>
                            </div>
                        </div>
                        <br/><br/>
                        <?php $tipCounter++; ?>
                    @endif

                @endforeach


            </div>
        </div>
    </div>
    <script>
        function likeTip(tipId, type) {
            const url = "{{ route('tips.like', ['id' => ':id']) }}";
            $.get(url.replace(':id', tipId) + '?type=' + type).then(function () {
                $('#likeTip-' + tipId).parent().remove();
            });
        }
    </script>

@stop

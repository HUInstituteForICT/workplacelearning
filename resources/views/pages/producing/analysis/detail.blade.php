<?php
/** @var App\Tips\EvaluatedTip[] $evaluatedTips */
/** @var App\Tips\EvaluatedTip $evaluatedTip */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('analysis.detail') }}
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
        <script>
            $(document).ready(function () {
                $(".expand-detail").click(function (e) {
                    $("#detail-" + ($(this).attr("data-id"))).toggle();
                    e.preventDefault();
                });
            });
        </script>

        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())

            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('analysis-producing-choice') }}" class="btn">{{__('analyses.back-to-choice')}}</a>
                    <h1>{{ __('tips.personal-tip') }}s</h1>


                    @if(count($evaluatedTips) > 0)
                        <?php $tipCounter = 1; ?>
                        <div style="display: flex; flex-wrap: wrap">
                            @foreach($evaluatedTips as $evaluatedTip)
                                @include('components.tip_card', ['title' => trans('analysis.tip') .' '. $tipCounter, 'saved' => $savedTips[$evaluatedTip->getTip()->id], 'evaluatedTip' => $evaluatedTip])
                                <?php ++$tipCounter; ?>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('tips.none') }}</p>
                    @endif

                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <h1>{{ __('analyses.analyses-statistics-title') }}</h1>

                    <h2>{{ __('analyses.time-per-category') }}</h2>
                    <canvas id="chart_hours"></canvas>
                    <script>
                        var canvasHours = document.getElementById('chart_hours');
                        var chart_hours = new Chart(canvasHours, {
                            type: 'pie',
                            data: {
                                labels: {!! $producingAnalysis->charts('hours')->labels->toJson() !!},
                                datasets: [{
                                    data: {!! $producingAnalysis->charts('hours')->data->toJson() !!},
                                    backgroundColor: [
                                        @foreach($producingAnalysis->charts('hours')->labels as $label)
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal')) !!}
                    <h2>{{ __('analyses.statistic') }}</h2>
                    <div class="form-group">
                        {!! Form::label('', __('analyses.average-difficulty'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('averageDifficulty') }}
                                ({{ __('analyses.10-most-complex') }})</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', __('analyses.percentage-difficult'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageDifficultTasks') }}
                                <b>{{ __('general.moeilijk') }}</b></p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', __('analyses.percentage-work-on-own'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageAloneHours') }}
                                {{ __('analyses.percentage-work-alone') }}</p></div>
                    </div>
                    {!! Form::close() !!}
                    <canvas id="chart_categories"></canvas>

                    <script>
                        var canvas_categories = document.getElementById("chart_categories");
                        var cat_chart = new Chart(canvas_categories, {
                            type: 'bar',
                            data: {
                                labels: {!! $producingAnalysis->charts('categories')->labels->toJson() !!},
                                datasets: [{
                                    label: '{{ __('Moeilijkheidsgraad op schaal van 1-10') }}',
                                    data: {!! $producingAnalysis->charts('categories')->data->toJson() !!},
                                    backgroundColor: [
                                        @foreach($producingAnalysis->charts('categories')->labels as $label)
                                        {{ "getChartColor(),"}}
                                        @endforeach
                                    ],
                                    borderColor: [],
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
                    <hr/>
                </div>
            </div>

        @endif

    </div>
    <script>
        function likeTip(tipId, type) {
            const url = "{{ route('tips.like', [':id']) }}";
            $.get(url.replace(':id', tipId) + '?type=' + type).then(function () {
                $('#likeTip-' + tipId).parent().remove();
            });
        }
    </script>

@stop

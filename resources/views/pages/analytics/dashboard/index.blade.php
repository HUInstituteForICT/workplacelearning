@extends('layout.HUdefault')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>@lang('dashboard.title')</h1>

                <button type="button" id="graphBtn" style="display: none" class="btn btn-info" data-toggle="modal" data-target="#showDetails">+</button>

                <div id="showDetails" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div id="GraphDetails" class="__reactRoot modal-content">
                            {{--Loading graph details page here--}}
                        </div>
                    </div>
                </div>

                <?php /** @var \App\DashboardChart $chart */ ?>
                @forelse($charts as $key => $chart)
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <div class="col-sm-8"><h3 class="panel-title">{{ $chart->chart->label }}</h3></div>
                                <div class="col-sm-4 text-right">
                                    <form action="{{ route('dashboard.move', [$chart->id, $chart->position, $chart->position - 1]) }}"
                                          style="display: inline-block;" method="post" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <button class="btn btn-default" title="Move up on the stack">&lsaquo;</button>
                                    </form>
                                    <form action="{{ route('dashboard.move', [$chart->id, $chart->position, $chart->position + 1]) }}"
                                          style="display: inline-block;" method="post" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <button class="btn btn-default" title="Move down on the stack">&rsaquo;</button>
                                    </form>
                                    <form action="{{ route('dashboard.delete', $chart->id) }}"

                                          style="display: inline-block;" method="post" accept-charset="UTF-8" class="frmDelete">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button class="btn btn-danger" title="Remove">&times;</button>
                                    </form>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-pencil"></span> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{route('analytics-edit', ['id' => $chart->chart->analysis_id])}}">{{__('analysis.analysis')}}</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="panel-body">
                                <canvas id="chart-{{ $key }}"></canvas>
                            </div>
                        </div>
                    </div>
                    @if ($chart === $charts->last())
                        <!--a href="{{ route('dashboard.add') }}" class="btn btn-primary" title="Add a chart">+</a-->
                    @endif
                @empty
                    <p>@lang('dashboard.empty')</p>
                @endforelse

                <p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAnalysis" onclick="Wizard.open()">@lang('dashboard.add-chart')</button>
                </p>

                <div id="addAnalysis" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">

                        <div id="QueryBuilder" class="modal-content"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let lastColorIndex = 0;

        function getChartColor(reset = false) {
            if (reset) {
                lastColorIndex = 0;
            }
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

        (function () {
            $('.frmDelete').on('submit', function (e) {
                if (!confirm('{{ __('dashboard.warning') }}')) {
                    e.preventDefault();
                    return false
                }
            });
            let analysisIDs = [];
            let labels = {!! json_encode($labels) !!};
            @foreach($charts as $key => $dchart)<?php $chart = $dchart->chart; ?>
            analysisIDs.push(JSON.parse('{!! json_encode($charts[$key]) !!}')['chart']['analysis_id']);

            let ctx{{ $key }} = $('#chart-{{ $key }}');
            let chart{{ $key }} = new Chart(ctx{{  $key }}, {
                type: '{{ $chart->type->slug }}',
                data: {
                    labels: <?php
                        $items = array_map(function ($k) use ($chart) {
                            return substr($k->{$chart->x_label->name}, 0, 37);
                        }, $chart->analysis->data['data']);
                        echo json_encode($items);
                        ?>,
                    datasets: [{
                        label: '{{ $chart->label }}',
                        backgroundColor: [

                            @foreach($chart->analysis->data['data'] as $c) {{-- For each data/column generate a color from a list --}}
                            @if($loop->first)
                            {{ "getChartColor(true),"}}
                            @else
                            {{ "getChartColor(),"}}
                            @endif

                            @endforeach
                        ],
                        // backgroundColor: defaultColours,
                        data: [<?php
                            $x_items = array_map(function ($k) use ($chart) {
                                return "'".$k->{$chart->y_label->name}."'";
                            }, $chart->analysis->data['data']);
                            echo join(', ', $x_items);
                            ?>]
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            @if($chart->type->slug == 'pie')
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                                var total = meta.total;
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = parseFloat((currentValue/total*100).toFixed(1));
                                return currentValue + ' (' + percentage + '%)';
                            },
                            title: function(tooltipItem, data) {
                                return data.labels[tooltipItem[0].index];
                            },
                            @endif
                            scales: {
                                @if($chart->type->slug != 'pie')
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                                @endif
                            }
                        }
                    },

                    onClick : function (event, array) {

                        if (array[0]) {
                            let index = array[0]['_index'];
                            if (index != null && index >= 0) {
                                let label = array[0]['_chart']['config']['data']['labels'][index];

                                let i = array[0]['_chart']['controller']['id'];
                                let analysisID = analysisIDs[i];

                                // The label is a description
                                if (analysisID != null && $.inArray(label, labels) >= 0) {
                                    label = label.replace(/ /g, '_');

                                    $('#GraphDetails').load('dashboard/chart_details/' + analysisID + "/" + label, function () {
                                        document.getElementById("graphBtn").click();
                                    });
                                }
                            }
                        }

                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            @endforeach
        })
        ();
    </script>
@stop
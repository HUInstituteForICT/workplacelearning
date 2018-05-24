@extends('layout.HUdefault')
@section('title', Lang::get('charts.title') . ' - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('charts.title') }}</h1>
                <h2>{{ $chart->label }}</h2>
                <div class="row">
                    <div class="col-sm-6">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-sm-6">
                        <form action="{{ route('analytics-expire') }}" method="post" accept-charset="UTF-8">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $chart->analysis->id }}">
                            <div class="form-group">
                                <button class="btn btn-primary"
                                        type="submit">{{ Lang::get('general.refresh') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {

            var ctxh = $('#myChart');
            var myCart = new Chart(ctxh, {
                type: '{{ $chart->type->slug }}', // ideally have the type itself make something nice out?
            data: {
                    labels: [<?php
                        $items = array_map(function ($key) use ($chart) {
                            return "'" . $key->{$chart->x_label->name} . "'";
                        }, $chart->analysis->data['data']);
                        echo join(', ', $items);
                        ?>],
                    datasets: [{
                        label: '{{ $chart->label }}',
                        backgroundColor: [
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
                            'rgba(255, 159, 64, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                        data: [<?php
                            $x_items = array_map(function ($key) use ($chart) {
                                return "'" . $key->{$chart->y_label->name} . "'";
                            }, $chart->analysis->data['data']);
                            echo join(', ', $x_items);
                            ?>]
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
            })
        })()
    </script>
@stop
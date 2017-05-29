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
                </div>
            </div>
        </div>
    </div>
    {{--<script src="/js/chartjs/Chart.bundle.min.js"></script>--}}
    <script>
      (function () {
        var ctx = $('#myChart')
        var myChart = new Chart(ctx, {
          type: '{{ $chart->type->slug }}', // ideally have the type itself poop something nice out?
          data: {
            labels: ['{{ $chart->analysis->data['data'][0]->{$chart->x_label->name} }}'],
            datasets: [{
              label: '{{ $chart->label }}',
              backgroundColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
              ],
              data: [<?php
                      $items = array_map(function ($key) use ($chart){
                          return $key->{$chart->y_label->name};
                      }, $chart->analysis->data['data']);
                      echo join(',', $items);
                 ?>]
            }],
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true
                  }
                }]
              }
            }
          }
        })
      })()
    </script>
@stop
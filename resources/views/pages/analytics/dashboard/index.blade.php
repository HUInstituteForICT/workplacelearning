@extends('layout.HUdefault')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('dashboard.title') }}</h1>
                @forelse($charts as $key => $chart)
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <div class="col-sm-9"><h3 class="panel-title">{{ $chart->chart->label }}</h3></div>
                                <div class="col-sm-3">
                                    <form action="{{ route('dashboard.move', [$chart->id, $chart->position, $chart->position - 1]) }}" style="display: inline-block;" method="post" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <button class="btn btn-default" title="Move up on the stack">&lsaquo;</button>
                                    </form>
                                    <form action="{{ route('dashboard.move', [$chart->id, $chart->position, $chart->position + 1]) }}" style="display: inline-block;" method="post" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <button class="btn btn-default" title="Move down on the stack">&rsaquo;</button>
                                    </form>
                                    <form action="{{ route('dashboard.delete', $chart->id) }}" class="frmDelete" style="display: inline-block;" method="post" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button class="btn btn-danger" title="Remove">&times;</button>
                                    </form>
                                </div>
                            </div>
                            <div class="panel-body">
                                <canvas id="chart-{{ $key }}"></canvas>
                            </div>
                        </div>
                    </div>
                    @if ($chart === $charts->last())
                        <a href="{{ route('dashboard.add') }}" class="btn btn-primary" title="Add a chart">+</a>
                    @endif
                @empty
                    <p>{{ Lang::get('dashboard.empty') }}</p>
                    <p>
                        <a href="{{ route('dashboard.add') }}" class="btn btn-primary">{{ Lang::get('add-chart') }}</a>
                    </p>
                @endforelse
            </div>
        </div>
    </div>
    <script>
    (function () {
      $('.frmDelete').on('submit', function (e) {
        if (!confirm('{{ Lang::get('dashboard.warning') }}')) {
          e.preventDefault()
          return false
        }
      })
      var defaultOptions = {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      };
      var defaultColours = [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ];
    @foreach($charts as $key => $dchart)<?php $chart = $dchart->chart ?>
  var ctx{{ $key }} = $('#chart-{{ $key }}')
      var chart{{ $key }} = new Chart(ctx{{  $key }}, {
        type: '{{ $chart->type->slug }}',
        data: {
          labels: [<?php
              $items = array_map(function ($k) use ($chart) {
                  return "'" . $k->{$chart->x_label->name} . "'";
              }, $chart->analysis->data['data']);
              echo join(', ', $items);
              ?>],
          datasets: [{
            label: '{{ $chart->label }}',
            backgroundColor: defaultColours,
            data: [<?php
                $x_items = array_map(function ($k) use ($chart){
                    return "'" . $k->{$chart->y_label->name} . "'";
                }, $chart->analysis->data['data']);
                echo join(', ', $x_items);
                ?>]
          }]
        },
        options: defaultOptions
      })
    @endforeach
})()
    </script>
@stop
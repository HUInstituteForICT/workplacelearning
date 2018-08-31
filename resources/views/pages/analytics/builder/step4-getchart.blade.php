<canvas id="myChart"></canvas>

<script>

    //Chart.defaults.global.animation.onComplete = () => {
    //console.log('finished');
    //};

    (function () {

        var ctxh = $('#myChart');
        var myChart = new Chart(ctxh, {
            type: '{{ $slug }}', // ideally have the type itself make something nice out?
            data: {
                labels: [<?php
                    $items = array_map(function ($key) use ($x_label) {
                        return "'".substr($key->{$x_label}, 0, 33)."'";
                    }, $result);
                    echo join(', ', $items);
                    ?>],
                datasets: [{
                    label: '{{ $title }}',
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
                        $x_items = array_map(function ($key) use ($y_label) {
                            return "'".$key->{$y_label}."'";
                        }, $result);
                        echo join(', ', $x_items);
                        ?>]
                }]
            },
            options: {
                tooltips: {
                    callbacks: {
                        @if($slug == 'pie')
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
                    }
                },
                scales: {
                    @if($slug != 'pie')
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                    @endif
                }
            }
        })
    })()
</script>
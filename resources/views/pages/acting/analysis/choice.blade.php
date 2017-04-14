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
                    var canvas_categories = document.getElementById("chart_timeslots");
                    var timeslots_chart = new Chart(canvas_categories, {
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

            </div>
        </div>
    </div>
@stop

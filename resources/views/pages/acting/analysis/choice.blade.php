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

            </div>
        </div>
    </div>
@stop

@extends('layout.HUdefault')
@section('title')
    Content Request
@stop
@section('content')
    <div class="container-fluid">
        @if(count($errors) > 0 || session()->has('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h2>Tips/Bugs</h2>
                <p>Heb je een tip hoe we deze site kunnen verbeteren, of heb je iets gevonden wat niet werkt? Laat het ons weten!</p>
                {!! Form::open(array('id' => 'taskForm', 'class' => 'form-horizontal well', 'url' => URL::to('bugreport/create', array(), true))) !!}
                    <input type="text" name="onderwerp" placeholder="Wat wil je doorgeven?" /><br><br />
                    <textarea style="max-width: 100%;" name="uitleg" cols="80" rows="10" placeholder="Bijvoorbeeld: Ik zou graag een andere rapportage terugzien op de analyse pagina. Kan deze worden toegevoegd?"></textarea><br />
                    <input type="submit" value="Verstuur" />
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function() {
                $("#rp_id").on('change', function(){
                    if($(this).val() == "new" && $(this).is(":visible")){
                        $("#cond-select-hidden").show();
                    } else {
                        $("#cond-select-hidden").hide();
                    }
                });
                $(".expand-click").click(function(){
                    $(".cond-hidden").hide();
                    $(this).siblings().show();
                    $("#cond-select-hidden").hide();
                    $("#rp_id").trigger("change");
                });
                $("#help-click").click(function(){
                    $('#help-text').slideToggle('slow');
                });
                $(".cond-hidden").hide();
                $("#cond-select-hidden").hide();
                $("#category").hide();
                $("#help-text").hide();
                $(".expand-click :input[value='persoon']").click();
                $("#newcat").click(function(){
                    $("#category").show();
                });

                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <div class="row">
            <div class="col-md-12 well">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Hoe werkt deze pagina?</h4>
                <div id="help-text">
                    <ol>
                        <li>Kies een datum waarop je de werkzaamheid hebt uitgevoerd. Deze mag alleen in het verleden of heden liggen.</li>
                        <li>Vul een omschrijving in van wat je hebt gedaan</li>
                        <li>Selecteer hoe je aan deze taak hebt gewerkt, of vul een nieuw verband toe. Heb je er alleen aan gewerkt of samen met iemand?</li>
                        <li>Selecteer de status van deze werkzaamheid. Is deze al afgerond of ben je er nog mee bezig?</li>
                        <li>Selecteer hoe moeilijk je deze taak vond. Liep je tegen problemen aan of ging het je goed af?</li>
                        <li>Klik op 'Opslaan'. De taak wordt onder in het scherm toegevoegd.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row well">
            <div class="col-md-2 form-group">
                <h4>Activiteit</h4>
                <input class="form-control fit-bs" type="date" name="datum" value="{{ date('Y-m-d', strtotime("now")) }}" /><br/>

                <h4>Omschrijving:</h4>
                <textarea class="form-control fit-bs" name="omschrijving" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19"></textarea>
            </div>
            <div class="col-md-2 form-group">
                <h4>Wanneer?</h4>
            </div>
            <div class="col-md-2 from-group">
                <h4>Met wie?</h4>
            </div>
            <div class="col-md-2 from-group">
                <h4>Met welke theorie?</h4>
            </div>
            <div class="col-md-2 from-group">
                <h4>Wat heb je geleerd?<br />Wat is het gevolg?</h4>
                <textarea class="form-control fit-bs" name="learned" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19"></textarea>
                <h4>Wat heb je hierbij nodig van je werkplek?</h4>
                <textarea class="form-control fit-bs" name="require_workplace" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19"></textarea>
                <h4>Wat heb je hierbij nodig van de HU?</h4>
                <textarea class="form-control fit-bs" name="required_school" required oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'\"]{3,80}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19"></textarea>
            </div>
            <div class="col-md-2 from-group">
                <h4>Leervraag</h4>
                <h4>Competentie</h4>
            </div>
        </div>
    </div>
@stop

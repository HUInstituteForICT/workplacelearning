<?php
/**
 * This file (calendar.blade.php) was created on 06/21/2016 at 13:04.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('general.calendar') }}
@stop
@section('content')
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.print.css" media="print"/>
    <style>
        .fc-day-header{
            background:  #00A1E2 !important;
            color: #FFFFFF;
        }
        .fc-event:hover{
            cursor:pointer;
        }
        .fc-sat, .fc-sun{
            background: #F7F7F7;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#delButton").hide(1);
            $("#calendar").fullCalendar({
                lang: '{{app()->getLocale()}}',
                defaultDate: '{{ date('Y-m-d', strtotime("now")) }}',
                height: 475,
                customButtons: {
                    newEvent: {
                        text: '{{ Lang::get('elements.calendar.btntext.newdeadline') }}',
                        click: function() {
                            if($("#newCalendarEvent").length) {
                                $('#eventForm').attr('action', '{{ route('deadline-create') }}');
                                $("#delButton").hide(1);
                            }
                        }
                    }
                },
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'newEvent'
                },
                eventLimit: true,
                eventColor: '#E72E2A',
                eventBackgroundColor: '#E72E2A',
                eventTextColor:'#FFFFFF',
                eventClick: function(calEvent, jsEvent, view) {
                    $('.fc-newEvent-button').trigger('click');
                    $('#eventForm').attr('action', '{{ route('deadline-update') }}');
                    $('#idDeadline').val(calEvent.id);
                    $('#nameDeadline').val(calEvent.title);
                    $('input[name="dateDeadline"]').val(calEvent.start.local().format('DD-MM-YYYY HH:mm'));
                    $("#delButton").show(1);
                },
                events: [
                    @foreach($deadlines as $dl)
                            {id:{{ $dl->dl_id }},title:'{{ $dl->dl_value }}',start:'{{ $dl->dl_datetime }}'},
                    @endforeach
                ],
                timeFormat: 'H:mm',
            });
        });
    </script>
    <div id='calendar'></div>
    <div id="newCalendarEvent" style="display: true;">
    {!! Form::open(array('id' => 'eventForm', 'class' => 'form-inline col-md-12 well', 'url' => route('deadline-create'))) !!}
        <input type="hidden" id="idDeadline" name="id" value="0" />
        <div class="col-sm-3 col-md-3">
        <div class="form-group">
            <label for="nameDeadline">{{ Lang::get('elements.calendar.labels.newdeadline') }}: </label>
            <input type="text" id="nameDeadline" name="nameDeadline" class="form-control" value="{{ old('nameDeadline') }}" placeholder="{{ Lang::get('elements.calendar.placeholders.description') }}" />
        </div>
        </div>

        <div class="col-sm-3 col-md-3">
        <div class="form-group">
            <div class='input-group date' id='date-deadline'>
                <input name="dateDeadline" type='text' class="form-control" value="{{ old('dateDeadline') }}"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        </div>
        <button type="submit" name="action" value="submit" class="btn btn-default">{{ Lang::get('elements.calendar.btntext.adddeadline') }}</button>
        <button type="submit" name="action" value="delete" class="btn btn-danger" id="delButton">{{ Lang::get('elements.calendar.btntext.removedeadline') }}</button>
        <script type="text/javascript">
            $(function () {
                $('#date-deadline').datetimepicker({
                    locale: 'nl',
                    daysOfWeekDisabled: [0,6],
                    minDate: "now",
                });
            });
        </script>
    {!! Form::close() !!}
    </div>
@stop

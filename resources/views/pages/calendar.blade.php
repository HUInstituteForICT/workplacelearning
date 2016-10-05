<?php
/**
 * This file (calendar.blade.php) was created on 06/21/2016 at 13:04.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Kalender
@stop
@section('content')
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.8.0/fullcalendar.print.css" media="print"/>
    @if(count($errors) > 0 || session()->has('success'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                    <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}: </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                </div>
            </div>
        </div>
    @endif
    <style>
        .fc-day-header{
            background:  #00A1E2 !important;
            color: #FFFFFF;
        }
        .fc-event:hover{
            cursor:pointer;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#calendar").fullCalendar({
                lang: '{{LaravelLocalization::getCurrentLocale()}}',
                defaultDate: '{{ date('Y-m-d', strtotime("now")) }}',
                height: 475,
                customButtons: {
                    newEvent: {
                        text: '{{ Lang::get('elements.calendar.btntext.newdeadline') }}',
                        click: function() {
                            if($("#newCalendarEvent").length) {
                                var nce = $("#newCalendarEvent").html();
                                $("#newCalendarEvent").remove();
                                $(".fc-toolbar").after(nce);
                                $(".fc-view-container").css("margin-top", "90px");
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
                    $('#eventForm').attr('action', '{{ URL::to('deadline/update', array(), true) }}');
                    $('#idDeadline').val(calEvent.id);
                    $('#nameDeadline').val(calEvent.title);
                    $('#dateDeadline').val(calEvent.start.local().format('YYYY-MM-DD[T]HH:mm'));
                    $("#delButton").show(1);
                },
                events: [
                    @foreach(Auth::user()->deadlines()->orderBy('dl_tijd', 'asc')->get() as $dl)
                            {id:{{ $dl->dl_id }},title:'{{ $dl->dl_value }}',start:'{{ $dl->dl_tijd }}'},
                    @endforeach
                ],
                timeFormat: 'H:mm',
            });
        });
    </script>
    <div id='calendar'></div>
    <div id="newCalendarEvent" style="display:none;">
    {!! Form::open(array('id' => 'eventForm', 'class' => 'form-inline col-md-12 well', 'url' => URL::to('deadline/create', array(), true))) !!}
        <input type="hidden" id="idDeadline" name="id" value="0" />
        <div class="form-group">
            <label for="nameDeadline">{{ Lang::get('elements.calendar.labels.newdeadline') }}: </label>
            <input type="text" id="nameDeadline" name="nameDeadline" class="form-control" placeholder="{{ Lang::get('elements.calendar.placeholders.description') }}" />
        </div>
        <div class="form-group">
            <label for="dateDeadline">{{ Lang::get('elements.calendar.labels.date') }}: </label>
            <input type="datetime-local" id="dateDeadline" name="dateDeadline" class="form-control" value="{{ date('Y-m-d', strtotime('+2 days')) ."T13:00" }}" />
        </div>
        <button type="submit" name="action" value="submit" class="btn btn-default">{{ Lang::get('elements.calendar.btntext.adddeadline') }}</button>
        <button type="submit" name="action" value="delete" class="btn btn-danger" id="delButton">{{ Lang::get('elements.calendar.btntext.removedeadline') }}</button>
    {!! Form::close() !!}
    </div>
@stop

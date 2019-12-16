<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Saved items
@stop
@section('content')
<?php
use App\Student;
use App\SavedLearningItem
/** @var Student $student */;
/** @var SavedLearningItem $sli */?>

    <div class="container-fluid">
        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-3">


                @card
                    <h1>{{ __('saved_learning_items.saved') }}</h1>
                    <h2>{{ __('saved_learning_items.timeline') }}</h2>

                    @foreach($sli as $item)
                    @if($item->category === 'tip')
                        @foreach($evaluatedTips as $evaluatedTip)
                        @if($evaluatedTip->getTip()->id == $item->item_id)
                        <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                             role="alert">
                            <h4>{{ __('tips.personal-tip') }}</h4>
                            <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
                        </div>
                        @endif
                        @endforeach
                    @endif
                    @endforeach

                @endcard

            </div>

         </div>
    </div>
@stop

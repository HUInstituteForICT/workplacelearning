<?php

use App\Tips\EvaluatedTip;
use App\Tips\Models\Tip;

/** @var Tip $tip */
/** @var EvaluatedTip $evaluatedTip */

$tip = $evaluatedTip->getTip();
?>

<div style="min-width: 300px; max-width: 500px; padding: 0px 20px;">
    @card
    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px">
        <strong>{{ $title }}</strong>

        @include('components.tip_bookmark', ['bookmarked' => $saved, 'id' => $tip->id])
    </div>

    <div style="margin-bottom: 30px;">
        <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
    </div>
    @if($tip->likes->count() === 0)
        <div style="display: flex; justify-content: space-evenly; align-items: center;">

            <span class="likeTip" style="background-color: #00A1E2;"
                  id="likeTip-{{ $tip->id }}"
                  onclick="likeTip({{ $tip->id }}, 1)"
                  target="_blank"><span class="glyphicon glyphicon-thumbs-up"></span>
            </span>
            <span class="likeTip" style="background-color: #e2423b;"
                  id="likeTip-{{ $tip->id }}"
                  onclick="likeTip({{ $tip->id }}, -1)"
                  target="_blank"><span class="glyphicon glyphicon-thumbs-down"></span>
            </span>
        </div>
    @endif
    @endcard
</div>

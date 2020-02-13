<?php
use App\Tips\EvaluatedTip;
use App\Tips\Models\Tip;

/** @var Tip $tip */
/** @var EvaluatedTip $evaluatedTip */

$tip = $evaluatedTip->getTip();
?>

<div style="max-width: 500px; padding: 0px 20px;">
    @card
    <div style="display: flex; justify-content: space-between; padding-bottom: 10px">
        <strong>{{ $title }}</strong>

        @include('components.tip_bookmark', ['bookmarked' => $saved, 'id' => $tip->id])
    </div>

    <div style="display: flex; justify-content: start; align-items: center;">
        @if($tip->likes->count() === 0)
            <div style="display: flex; flex-direction: column; justify-content: space-between; padding-right: 20px">
                <h2 class="h2" style="cursor: pointer;color: #00A1E2;"
                    id="likeTip-{{ $tip->id }}"
                    onclick="likeTip({{ $tip->id }}, 1)"
                    target="_blank"><span class="glyphicon glyphicon-thumbs-up"/></h2>
                <h2 class="h2" style="cursor: pointer;color: #e2423b;"
                    id="likeTip-{{ $tip->id }}"
                    onclick="likeTip({{ $tip->id }}, -1)"
                    target="_blank"><span class="glyphicon glyphicon-thumbs-down"/></h2>
            </div>
        @endif
        <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
    </div>
    @endcard
</div>

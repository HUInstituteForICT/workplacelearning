<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Requests\LearningActivity\FeedbackCreateRequest;
use Illuminate\Http\RedirectResponse;

class FeedbackController
{
    public function show(Feedback $feedback)
    {
        return view('pages.producing.feedback')
            ->with('lap', $feedback->learningActivityProducing)
            ->with('feedback', $feedback);
    }

    public function update(FeedbackCreateRequest $request, Feedback $feedback): RedirectResponse
    {
        $feedback->notfinished = ($request->get('notfinished') === 'Anders') ? $request->get('newnotfinished') : $request->get('notfinished');
        $feedback->initiative = $request->get('initiatief');
        $feedback->progress_satisfied = $request->get('progress_satisfied');
        $feedback->support_requested = $request->get('support_requested');
        $feedback->supported_provided_wp = $request->get('supported_provided_wp');
        $feedback->nextstep_self = $request->get('vervolgstap_zelf');
        $feedback->support_needed_wp = !$request->has('ondersteuningWerkplek') ? $request->get('ondersteuning_werkplek') : 'Geen';
        $feedback->support_needed_ed = !$request->has('ondersteuningOpleiding') ? $request->get('ondersteuning_opleiding') : 'Geen';
        $feedback->save();

        return redirect()->route('process-producing')->with(
            'success',
            __('activity.feedback-activity-saved')
        );
    }
}

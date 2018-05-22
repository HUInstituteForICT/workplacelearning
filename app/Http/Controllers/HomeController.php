<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use App\Tips\ApplicableTipFetcher;
use App\Tips\DataCollectors\Collector;
use App\Tips\Tip;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;

class HomeController extends Controller
{

    public function showHome()
    {
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate(Request $request, ApplicableTipFetcher $applicableTipFetcher)
    {
        /** @var WorkplaceLearningPeriod $workplaceLearningPeriod */
        $workplaceLearningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod();
        $collector = new Collector(null, null, $workplaceLearningPeriod);
        $tips = collect($applicableTipFetcher->fetchForCohort($workplaceLearningPeriod->cohort, $collector))
            ->filter(function (Tip $tip) use ($request) {
                return !$tip->dislikedByStudent($request->user());
            });

        $tip = $tips->count() > 0 ? $tips->random(null) : null;

        return view('pages.producing.home', ['tip' => $tip]);
    }

    public function showActingTemplate(Request $request, ApplicableTipFetcher $applicableTipFetcher)
    {
        /** @var WorkplaceLearningPeriod $workplaceLearningPeriod */
        $workplaceLearningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod();
        $collector = new Collector(null, null, $workplaceLearningPeriod);
        $tips = collect($applicableTipFetcher->fetchForCohort($workplaceLearningPeriod->cohort,
            $collector))->filter(function (Tip $tip) use ($request) {
            return !$tip->dislikedByStudent($request->user());
        });

        $tip = $tips->count() > 0 ? $tips->random(null) : null;

        return view('pages.acting.home', ['tip' => $tip]);
    }

    public function showDefault()
    {
        return redirect()->route('home');
    }

    public function showBugReport()
    {
        return view('pages.bugreport');
    }

    public function createBugReport(Request $request, Mailer $mailer)
    {
        $validator = Validator::make($request->all(), [
            'onderwerp' => 'required|max:40|min:3',
            'uitleg'    => 'required|max:800|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->route('bugreport')
                ->withErrors($validator)
                ->withInput();
        }

        $mailer->send(new FeedbackGiven($request, Auth::user()));

        return redirect()->route('home')->with('success', Lang::get('general.bugreport-sent'));
    }
}

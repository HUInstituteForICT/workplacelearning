<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function showHome()
    {
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate()
    {
        return view('pages.producing.home');
    }

    public function showActingTemplate()
    {
        return view('pages.acting.home');
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
            'onderwerp' => 'required|regex:/^[0-9a-zA-Z ()-?!%#@,.]*$/|max:40|min:3',
            'uitleg'    => 'required|regex:/^[0-9a-zA-Z ()-?!%#@,.]*$/|max:800|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->route('bugreport')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();


        $mailer->to($user->email)->send(new FeedbackGiven($request, $user));

        return redirect()->route('home')->with('success', 'Bedankt voor je bijdrage! Je krijgt per email een reactie terug.');
    }
}

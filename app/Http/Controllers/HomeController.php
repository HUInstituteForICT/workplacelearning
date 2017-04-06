<?php

namespace App\Http\Controllers;

use App\EducationProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;

class HomeController extends Controller{

    public function showHome(){
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate(){
        return view('pages.producing.home');
    }

    public function showActingTemplate(){
        return view('pages.acting.home');
    }

    public function showDefault(){
        return redirect()->route('home');
    }

    public function showBugReport(){
        return view('pages.bugreport');
    }

    public function createBugReport(Request $request){
        $validator = Validator::make($request->all(), [
            'onderwerp' => 'required|regex:/^[0-9a-zA-Z ()-?!%#@,.]*$/|max:40|min:3',
            'uitleg'    => 'required|regex:/^[0-9a-zA-Z ()-?!%#@,.]*$/|max:800|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->route('bugreport')
                ->withErrors($validator)
                ->withInput();
        }

        Mail::send(
            'templates.bugreport-email',
            [
                'student_name'  => Auth::user()->getInitials()." ".Auth::user()->lastname." (".Auth::user()->firstname.")",
                'student_email' => Auth::user()->email,
                'education'     => EducationProgram::find(Auth::user()->ep_id),
                'subject'       => $request['onderwerp'],
                'content'       => $request['uitleg'],
            ],
            function($message){
                $message->subject('Tip/Bug ingezonden!');
                $message->from('debug@werkplekleren.hu.nl', 'Werkplekleren @ Hogeschool Utrecht');
                $message->to('max.cassee@hu.nl');
                $message->cc('esther.vanderstappen@hu.nl');
                $message->cc('dylan.vangils@student.hu.nl');
                $message->replyTo(Auth::user()->email);
            }
        );
        return redirect()->route('home')->with('success', 'Bedankt voor je bijdrage! Je krijgt per email een reactie terug.');
    }
    
}

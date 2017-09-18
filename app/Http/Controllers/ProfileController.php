<?php
/**
 * This file (ProfileController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use App\Student;
use Illuminate\Foundation\Auth\User;
use IntlDateFormatter;

use \Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('pages.profile');
    }

    public function update(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|max:255|min:3',
            'lastname'      => 'required|max:255|min:3',
            'email'         => 'required|email|max:255|unique:student,email,'.$request->student_id.',student_id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All ok.
            // Todo why find user when already authenticated?
            $user = Student::find(Auth::user()->student_id);
            $user->firstname    = $request->firstname;
            $user->lastname  = $request->lastname;
            $user->email       = $request->email;
            //$user->telefoon    = $request->phone;
            $user->save();
            return redirect()->route('profile')->with('success', 'De wijzigingen zijn opgeslagen.');
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}

<?php
/**
 * This file (ProfileController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Validator;

class ProfileController extends Controller
{
    public function show()
    {
        return view('pages.profile')
            ->with('locales', Student::$locales);
    }

    public function update(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255|min:3',
            'lastname' => 'required|max:255|min:3',
            'email' => 'required|email|max:255|unique:student,email,'.$request->student_id.',student_id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        }
        // All ok.
        // Todo why find user when already authenticated?
        $user = Student::find(Auth::user()->student_id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->locale = $request->get('locale');
        //$user->telefoon    = $request->phone;
        $user->save();

        return redirect()->route('profile')->with('success', Lang::get('general.edit-saved'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        // Just extend here, we don't need it anywhere else
        Validator::extend('validPassword', function ($attribute, $value, $parameters, $validator) use ($user) {
            return Hash::check($value, $user->pw_hash);
        });

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|validPassword',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user->pw_hash = Hash::make($request->get('new_password'));
        $user->save();

        return redirect()->route('profile')->with('success', Lang::get('general.edit-saved'));
    }
}

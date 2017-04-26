<?php
/**
 * This file (ProfileController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use IntlDateFormatter;
use App\User;
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
            'firstname'     => 'required|regex:/^[a-zA-Z -]*$/|max:255|min:3',
            'lastname'      => 'required|regex:/^[a-zA-Z -]*$/|max:255|min:3',
            'email'         => 'required|email|max:255|unique:students,email,'.$request->stud_id.',stud_id',
            //'phone'         => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All ok.
            // Todo why find user when already authenticated?
            $user = User::find(Auth::user()->stud_id);
            $user->voornaam    = $request->firstname;
            $user->achternaam  = $request->lastname;
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

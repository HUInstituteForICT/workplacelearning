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

class ProfileController extends Controller{
    public function show(){
        return view('pages.profile');
    }
    
    public function update(Request $request){
        // Validate the input        
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|regex:/^[a-zA-Z -]*$/|max:255|min:3',
            'lastname'      => 'required|regex:/^[a-zA-Z -]*$/|max:255|min:3',
            'phone'         => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
        ]);

        if ($validator->fails()) {
            return redirect('profiel')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All ok.
            $u = User::find(Auth::user()->stud_id);
            $u->voornaam    = $request->firstname;
            $u->achternaam  = $request->lastname;
            $u->telefoon    = $request->phone;
            $u->save();

            return redirect('profiel')->with('success', 'De wijzigingen zijn opgeslagen.');
        }
    }

    public function __construct(){
        $this->middleware('auth');
    }

}
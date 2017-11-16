<?php

namespace App\Http\Controllers;

use App\Student;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Request;

class LocaleSwitcher extends Controller
{

    public function switchLocale(Request $request) {
        if(!in_array($request->get('locale'), array_keys(Student::$locales))) {
            throw new Exception("Selected unsupported locale");
        }
        Session::put('locale', $request->get('locale'));
        Session::save();
        if(Auth::check()) {
            Auth::user()->locale = $request->get('locale');
            Auth::user()->save();
        }

        App::setLocale(Session::get('locale'));

        return redirect($request->get('previousPage', url('/')));
    }

}
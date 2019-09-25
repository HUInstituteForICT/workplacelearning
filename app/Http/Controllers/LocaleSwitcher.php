<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Request;

class LocaleSwitcher extends Controller
{
    public function switchLocale(Request $request)
    {
        if (!array_key_exists($request->get('locale'), Student::$locales)) {
            return redirect(route('home'));
        }

        Session::put('locale', $request->get('locale'));
        Session::save();
        if (Auth::check()) {
            Auth::user()->locale = $request->get('locale');
            Auth::user()->save();
        }

        App::setLocale(Session::get('locale'));

        return redirect($request->get('previousPage', url('/')));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showPasswordResetForm(){
        return view('auth.passwords.reset');
    }

    public function reset(Request $r){
        // Validate the user exists and the token matches, then update the user with the new password
        $v = Validator::make($r->all(), [
            "email"     => "required|email|max:255|exists:students",
            "answer"    => "required|min:3|max:30",
            "password"  => "required|min:8|confirmed",
        ]);
        if ($v->fails()) {
            return redirect('reset/password')
                ->withErrors($v)
                ->withInput();
        }

        $u = User::where('email', $r['email'])->first();
        if (!$u || $u->answer !== $r['answer']) {
            return redirect('reset/password')
                ->withErrors(['De ingegeven informatie komt niet overeen met die in onze database.'])
                ->withInput();
        }

        if(bcrypt($r['password']) === $u->pw_hash){
            Auth::guard($this->getGuard())->login($u);
        }

        $u->pw_hash = bcrypt($r['password']);
        $u->save();
        Auth::guard($this->getGuard())->login($u);
        return redirect('home')->with('success', 'Je wachtwoord is succesvol aangepast.');
    }

    // Override to implement custom pw_hash field and remove the remember_me token
    protected function resetPassword($user, $password){
        return redirect('home');
    }

    // Override to disable token-based password resets
    public function sendResetLinkEmail(){ return redirect('login'); }
}

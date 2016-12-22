<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'studentnummer' => 'required|digits:7|unique:students',
            'firstname'     => 'required|max:255|min:3',
            'lastname'      => 'required|max:255|min:3',
            'gender'        => 'required|in:male,female',
            //'birthdate'     => 'required|date|before:'.date("Y-m-d", strtotime('-17 years')),
            'email'         => 'required|email|max:255|unique:students',
            //'phone'         => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            'password'      => 'required|min:8|confirmed',
            'secret'        => 'required|in:ICTstage2016',
            'answer'        => 'required|min:3|max:30',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'studentnummer'     => $data['studentnummer'],
            'voornaam'          => $data['firstname'],
            'achternaam'        => $data['lastname'],
            'pw_hash'           => bcrypt($data['password']),
            'geslacht'          => strtoupper(substr($data['gender'], 0, 1)),
            'geboortedatum'     => date("Y-m-d", strtotime('01-01-1900')),   //$data['birthdate'],
            'email'             => $data['email'],
            'telefoon'          => "00-00000000",  //$data['phone'],
            'datumregistratie'  => date('Y-m-d H:i:s'),
            'answer'            => $data['answer'],
        ]);
    }
}

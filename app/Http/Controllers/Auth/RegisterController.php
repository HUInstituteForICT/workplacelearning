<?php

namespace App\Http\Controllers\Auth;

use App\Student;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'studentnr'     => 'required|digits:7|unique:student',
            'firstname'     => 'required|max:255|min:3',
            'lastname'      => 'required|max:255|min:3',
            'gender'        => 'required|in:male,female',
            //'birthdate'     => 'required|date|before:'.date("Y-m-d", strtotime('-17 years')),
            'email'         => 'required|email|max:255|unique:student',
            //'phone'         => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            'password'      => 'required|min:8|confirmed',
            'secret'        => 'required|in:ICTstage2016,Stage2017',
            //'answer'        => 'required|min:3|max:30',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Student
     */
    protected function create(array $data)
    {
        return Student::create([
            'studentnr'         => $data['studentnr'],
            'firstname'         => $data['firstname'],
            'lastname'          => $data['lastname'],
            'ep_id'             => $data['education'],
            'pw_hash'           => bcrypt($data['password']),
            'gender'            => strtoupper(substr($data['gender'], 0, 1)),
            //'birthdate'        => $data['birthdate'],     // Deprecated
            'email'             => $data['email'],
            //'phonenr'          => $data['phone'],         // Deprecated
            'registrationdate'  => date('Y-m-d H:i:s'),
            //'answer'            => $data['answer'],       // Deprecated
        ]);
    }
}

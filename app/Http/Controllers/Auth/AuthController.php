<?php

namespace App\Http\Controllers\Auth;

use App\Student;
use App\EducationProgram;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/home';

    public function __construct(){
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    protected function validator(array $data){
        return Validator::make($data, [
            'studentnr'     => 'required|digits:7|unique:student',
            'firstname'     => 'required|max:255|min:3',
            'lastname'      => 'required|max:255|min:3',
            'gender'        => 'required|in:male,female',
            'email'         => 'required|email|max:255|unique:student',
            'password'      => 'required|min:8|confirmed',
            'secret'        => 'required|in:ICTstage2016',
            //'phone'         => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            //'birthdate'     => 'required|date|before:'.date("Y-m-d", strtotime('-17 years')),
            //'answer'        => 'required|min:3|max:30',
        ]);
    }
    
    protected function create(array $data){
        return Student::create([
            'studentnr'         => $data['studentnr'],
            'firstname'         => $data['firstname'],
            'lastname'          => $data['lastname'],
            'ep_id'             => $data['education'],
            'pw_hash'           => bcrypt($data['password']),
            'gender'            => strtoupper(substr($data['gender'], 0, 1)),
            'email'             => $data['email'],
            'registrationdate'  => date('Y-m-d H:i:s'),
            //'phonenr'          => $data['phone'],         // Deprecated
            //'birthdate'        => $data['birthdate'],     // Deprecated
            //'answer'            => $data['answer'],       // Deprecated
        ]);
    }


    public function showRegistrationForm(){
        // Retrieve all educationprogram data from DB and pass on to view
        $programs = EducationProgram::all();

        if (property_exists($this, 'registerView')) {
            return view($this->registerView);
        }

        return view('auth.register')->with('educationprograms', $programs);
    }
}

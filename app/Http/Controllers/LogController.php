<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class LogController extends Controller{

    public function log(Request $r){
        $validator = Validator::make($r->all(), [
            'width'     => 'required|integer|min:1|max:20000',
            'height'    => 'required|integer|min:1|max:20000',
            'agent'     => 'required|string|min:1|max:1000',
            'OS'        => 'required|string|min:1|max:1000',
            'url'       => 'required|url',
        ]);
        if ($validator->fails()) return;
        $result = DB::table('accesslog')->insert(
            [
                'student_id'    =>  Auth::user()->student_id,
                'session_id'    => $r->session()->getId(),
                'user_ip'       => $r->ip(),
                'screen_width'  => $r['width'],
                'screen_height' => $r['height'],
                'user_agent'    => $r['agent'],
                'OS'            => $r['OS'],
                'url'           => $r['url'],
                'timestamp'     => date_format(date_create(null, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s'),
            ]
        );
    }

    public function __construct(){
        $this->middleware('auth');
    }
}

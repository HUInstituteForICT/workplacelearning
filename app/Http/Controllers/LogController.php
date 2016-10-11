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
        if ($validator->fails()) return 0;
        $data =             [
            'user_id'       =>  Auth::user()->stud_id,
            'session_id'    => $r->session()->getId(),
            'screen_width'  => $r['width'],
            'screen_height' => $r['height'],
            'user_agent'    => $r['agent'],
            'OS'            => $r['OS'],
            'url'           => $r['url'],
            'timestamp'     => time(),
        ];
        $result = DB::table('access_log')->insert(
            $data
        );
        return json_encode($result);

        return 1;
    }

    public function __construct(){
        $this->middleware('auth');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class LogController extends Controller
{
    public function log(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'width'  => 'required|integer|min:1|max:20000',
            'height' => 'required|integer|min:1|max:20000',
            'agent'  => 'required|string|min:1|max:1000',
            'OS'     => 'required|string|min:1|max:1000',
            'url'    => 'required|url',
        ]);
        if ($validator->fails()) {
            return;
        }
        $result = DB::table('accesslog')->insert(
            [
                'student_id'    => Auth::user()->student_id,
                'session_id'    => $request->session()->getId(),
                'user_ip'       => $request->ip(),
                'screen_width'  => $request['width'],
                'screen_height' => $request['height'],
                'user_agent'    => $request['agent'],
                'OS'            => $request['OS'],
                'url'           => $request['url'],
                'timestamp'     => date_format(date_create(null, timezone_open('Europe/Amsterdam')), 'Y-m-d H:i:s'),
            ]
        );
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}

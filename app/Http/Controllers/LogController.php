<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
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
                'timestamp'     => Carbon::now(),
            ]
        );
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}

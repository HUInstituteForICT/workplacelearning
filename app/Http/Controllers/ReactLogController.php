<?php


namespace App\Http\Controllers;


use App\ReactLog;
use Illuminate\Http\Request;

class ReactLogController
{
    public function store(Request $request) {
        return ReactLog::create(array_merge($request->all(), ['fixed' => false]));
    }

    public function update(Request $request, ReactLog $reactLog) {
        return $reactLog->update($request->all());
    }

    public function index() {
        return view('pages.reactlogs', ['logs' => ReactLog::orderBy('id', 'desc')->get()]);
    }

    public function fix(ReactLog $reactLog) {
        $reactLog->update(['fixed' => true]);
        return redirect()->route('reactlogs');
    }

}
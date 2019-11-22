<?php

declare(strict_types=1);
/**
 * This file (CalendarController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Deadline;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function show()
    {
        return view('pages.calendar')
            ->with('deadlines', Auth::user()->deadlines()->orderBy('dl_datetime', 'asc')->get());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'nameDeadline' => 'required|max:255|min:3',
           'dateDeadline' => 'required|date|after:'.date('Y-m-d', strtotime('now')),
        ]);
        if ($validator->fails()) {
            return redirect()->route('deadline')
                ->withErrors($validator->errors())
                ->withInput();
        }

        $deadline = new Deadline();
        $deadline->student_id = Auth::user()->student_id;
        $deadline->dl_value = $request['nameDeadline'];
        $deadline->dl_datetime = date('Y-m-d H:i:s', strtotime($request['dateDeadline']));
        $deadline->save();

        return redirect()->route('deadline')->with('success', __('general.calendar-deadline-saved'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'           => 'required|exists:deadline,dl_id',
            'action'       => 'required|in:submit,delete',
            'nameDeadline' => 'required|max:255|min:3',
            'dateDeadline' => 'required|date_format:d-m-Y H:i',
        ]);
        if ($validator->fails()) {
            return redirect('deadline')
                ->withErrors($validator)
                ->withInput();
        }

        $deadline = Deadline::find($request['id']);
        if (is_null($deadline) || $deadline->student_id != Auth::user()->student_id) {
            return redirect('deadline')->withErrors(['error', __('general.calendar-deadline-permission')]);
        } elseif ($request->input('action') === 'submit') {
            $deadline->dl_value = $request['nameDeadline'];
            $deadline->dl_datetime = date('Y-m-d H:i:s', strtotime($request['dateDeadline']));
            $deadline->save();
            $msg = __('general.calendar-deadline-edited');
        } elseif ($request->input('action') === 'delete') {
            $deadline->delete();
            $msg = __('general.calendar-deadline-removed');
        } else {
            return redirect()->route('deadline')->withErrors(['error', __('errors.occurred')]);
        }

        return redirect()->route('deadline')->with('success', $msg);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}

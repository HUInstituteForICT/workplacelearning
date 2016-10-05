<?php
/**
 * This file (CalendarController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace app\Http\Controllers;

use App\Deadline;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller{
    public function show(){
        return view('pages.calendar');
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
           'nameDeadline'   => 'required|regex:/^[0-9a-zA-Z ()-]*$/|max:255|min:3',
           'dateDeadline'   => 'required|date|after:'.date('Y-m-d', strtotime("now")),
        ]);
        if($validator->fails()){
            return redirect('deadline')
                ->withErrors($validator->errors())
                ->withInput();
        } else {
            $d = new Deadline;
            $d->student_id      = Auth::user()->stud_id;
            $d->dl_value        = $request->nameDeadline;
            $d->dl_tijd         = $request->dateDeadline;
            $d->save();
            return redirect('deadline')->with('success', "De deadline is opgeslagen.");
        }
    }

    public function update(Request $r){
        $validator = Validator::make($r->all(), [
            'id'                => 'required|exists:deadlines,dl_id',
            'action'            => 'required|in:submit,delete',
            'nameDeadline'      => 'required|regex:/^[0-9a-zA-Z ()-]*$/|max:255|min:3',
            'dateDeadline'      => 'required|date',
        ]);
        if($validator->fails()){
            return redirect('deadline')
                ->withErrors($validator)
                ->withInput();
        } else {
            // We know the deadline exists. Verify it belongs to the user and redirect accordingly
            $d = Deadline::find($r['id']);

            if($d->student_id != Auth::user()->stud_id){
                return redirect('kalender')->withErrors(['nobelong', "Je kan deze deadline niet wijzigen."]);
            }

            // The event exists and belongs to the user. Now update or delete accordingly.
            if($r->input('action') === "submit"){
                $d->dl_value        = $r->nameDeadline;
                $d->dl_tijd         = $r->dateDeadline;
                $d->save();
                $msg = "De deadline is aangepast.";
            } elseif($r->input('action') === "delete") {
                $d->delete();
                $msg = "De deadline is verwijderd uit je agenda.";
            }
            return redirect('deadline')->with('success', $msg);
        }
    }
    public function __construct(){
        $this->middleware('auth');
    }
}
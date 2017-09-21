<?php


namespace App\Http\Controllers;


use App\Mail\TxtExport;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityExportController extends Controller
{
    public function exportMail(Request $request) {

        Mail::to($request->get('email'))->send(new TxtExport($request->get('txt')));

        return \response(json_encode(["status" => "success"]));

    }
}
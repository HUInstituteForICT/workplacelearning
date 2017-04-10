<?php
/**
 * This file (ActingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\LearningActivityActing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActingAnalysisController extends Controller
{

    public function show()
    {
        return view('pages.acting.analysis.choice');
    }
}

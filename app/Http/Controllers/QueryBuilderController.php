<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\AnalysisChart;
use App\DashboardChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class QueryBuilderController extends Controller
{

    public function __construct()
    {
    }

    public function showStep($id)
    {
        switch($id) {
            case 1: return view("pages.analytics.builder.step1-type"); break;
            case 2: return view("pages.analytics.builder.step2-builder"); break;
            case 3: return view("pages.analytics.builder.step3-builder-filters"); break;
            case 4: return view("pages.analytics.builder.step4-chart"); break;
        }
    }
}
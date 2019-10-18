<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function __invoke()
    {
        return view('pages.teacher.dashboard');
    }
}

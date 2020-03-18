<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Sodium\add;

class StudentCsvImportController extends Controller
{
    public function save(Request $request)
    {
        if($request->hasFile('file'))
        {
            $filepath = $request->file('file')->getRealPath();
            $file = fopen($filepath, "r");
            $dumpArray = [];

            while (($getData = fgetcsv($file, ",")) !== FALSE)
            {
                if (!$getData[0] == "" && !$getData[1] == "")
                {
                    $getData[0] =
                    $getData[1] =
                    $getData[2] =
                    $getData[3] =
                    $getData[4] =
                    $getData[5] =
                    $getData[6] =


                    array_push($dumpArray, $getData);
                }
            }
        }
        dd($dumpArray);

        return view('pages.admin.dd-import');
        }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Sodium\add;
use App\Student;

class StudentCsvImportController extends Controller
{
    public function save(Request $request)
    {
        if($request->hasFile('file'))
        {
            $filepath = $request->file('file')->getRealPath();
            $file = fopen($filepath, "r");
            $dumpArray = [];
            $count = 0;

            $student = new Student();

            while (($getData = fgetcsv($file, ",")) !== FALSE)
            {
                if($count >= 5) {
                    if (!$getData[0] == "" && !$getData[1] == "") {


                        // Difficulty, Category, Status, Duration zijn verplichte Foreign Keys
                        // Bovenstaande moeten eerst ingeschoten worden voordat date kan.


                        $timestamp = strtotime($getData[0]);
                        $omschrijving = $getData[1];
                        $duur = $getData[2];
                        $categorie = $getData[3];
                        $werkenLerenMet = $getData[4];
                        $status = $getData[5];
                        $moeilijkheidsgraad = $getData[6];

                        dd($timestamp,
                            $omschrijving,
                            $duur,
                            $categorie,
                            $werkenLerenMet,
                            $status,
                            $moeilijkheidsgraad
                        );

//                        $student->save($timestamp);


                        //                    $getData[1] =
                        //                    $getData[2] =
                        //                    $getData[3] =
                        //                    $getData[4] =
                        //                    $getData[5] =
                        //                    $getData[6] =


//                        array_push($dumpArray, $getData);
                    }
                }
                $count++;
            }
        }
        dd($dumpArray);

        return view('pages.admin.dd-import');
        }
}

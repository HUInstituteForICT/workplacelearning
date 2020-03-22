<?php

namespace App\Http\Controllers;

use App\Services\Factories\LAPFactory;
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
            $count = 0;

            $lapFactory = new LAPFactory();

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
//
//                        dd($timestamp,
//                            $omschrijving,
//                            $duur,
//                            $categorie,
//                            $werkenLerenMet,
//                            $status,
//                            $moeilijkheidsgraad
//                        );



                        switch ($categorie) {
                            case 'Onderzoek':
                                // TODO: Implement a check to see which cohort_id it is.
                                // For example:
                                // if(cohort_id = 40){ $category = 834}
                                // if(cohort_id = 41) {$category = 849};
                                $categorie = 834;
                                break;
                            case 'ICT-Documentatie':
                                $categorie = 835;
                                break;
                            case 'Schooldocumentatie':
                                $categorie = 836;
                                break;
                            case 'Overleg':
                                $categorie = 837;
                                break;
                            case 'Programmeren':
                                $categorie = 846;
                                break;
                            case 'Analyseren & Ontwerpen':
                                $categorie = 847;
                                break;
                            case 'Testen':
                                $categorie = 848;
                                break;
                        }



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

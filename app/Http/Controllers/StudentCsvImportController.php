<?php

namespace App\Http\Controllers;

use App\Services\CustomProducingEntityHandler;
use App\Services\Factories\LAPFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function Sodium\add;

class StudentCsvImportController extends Controller
{
    public function show(Request $request) {

        return view('pages.producing.activity-import');
    }

    public function save(Request $request,
                         LAPFactory $LAPFactory
                        )
    {
//        $request->validate(["csv_file" => 'required']);

        if($request->hasFile('file'))
        {
            $filepath = $request->file('file')->getRealPath();
            $file = fopen($filepath, "r");
            $count = 0;

            while (($getData = fgetcsv($file, ",")) !== FALSE)
            {
                    if ($count >= 5 && !$getData[0] == "" && !$getData[1] == "") {
                        $data = [];

                        $data['category_id'] = $getData[3];
                        $data['omschrijving'] = $getData[1];
                        $data['aantaluren'] = $getData[2];
                        $data['aantaluren_custom'] = '';
                        $data['datum'] = $getData[0];
                        $data['extrafeedback'] = '';
                        $data['moeilijkheid'] = $getData[6];
                        $data['status'] = $getData[5];

                        if(strtolower(substr($getData[4], 0, 7)) === 'persoon') {
                            $resourceExplode = explode('- ', $getData[4]);
                            $data['resource'] = $resourceExplode[0];
                            $data['resource_person_id'] = $resourceExplode[1];
                        }
                        else {
                            $data['resource'] = $getData[4];
                        }

                        $data['internetsource'] = '';
                        $data['booksource'] = '';
                        $data['chain_id'] = '';

                        $LAPFactory->createLAP($data);
                    }
                $count++;
            }
        }

        return view('pages.admin.dd-import');
        }

        private function checkCategory($categorie)
        {
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
        }
}

<?php

namespace App\Http\Controllers;

use App\Services\Factories\LAPFactory;
use Illuminate\Http\Request;

class StudentCsvImportController extends Controller
{
    public function show(Request $request) {

        return view('pages.producing.activity-import');
    }

    public function save(Request $request,
                         LAPFactory $LAPFactory
                        )
    {
        $request->validate(["csv_file" => 'required']);

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
                        $data['aantaluren_custom'] = $getData[2] * 60;
                        $data['datum'] = $getData[0];
                        $data['extrafeedback'] = '';

                        switch($getData[6]) {
                            case 'Makkelijk':
                                $data['moeilijkheid'] = 1;
                                break;
                            case 'Gemiddeld':
                                $data['moeilijkheid'] = 2;
                                break;
                            case 'Moeilijk':
                                $data['moeilijkheid'] = 3;
                                break;
                        }

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

        return view('pages.producing.activity-import')->with('successMsg', 'works');
        }
}

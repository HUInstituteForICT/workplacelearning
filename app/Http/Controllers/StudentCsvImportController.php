<?php

namespace App\Http\Controllers;



use App\Repository\Eloquent\CategoryRepository;
use App\Services\Factories\LAPFactory;
use App\Student;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\Array_;


class StudentCsvImportController extends Controller
{
    //Verplichte velden: duration, description, date, category, difficulty, status
    //CustomHandler schrijven die CSV entries checkt!


    public function csvValidator(Request $request) {

        $request->validate(["csv_file" => 'required|mimes:csv,txt']);

        if($request->hasFile('csv_file')) {
            $filepath = $request->file('csv_file')->getRealPath();
            $file = fopen($filepath, "r");
            $count = 0;
            $getData = fgetcsv($file, ",");

            $validate_array = [];

            while ($getData !== FALSE) {
                if ($count >= 5 && !$getData[0] == "" && !$getData[1] == "") {

                    $validator = Validator::make($request->all(), [
                        //Verplichte velden: duration, description, date, category, difficulty, status
                        $getData[2] => 'required|min:0|max:24',
                        $getData[1] => 'required|max:1000',
                        $getData[0] => 'required|after:tomorrow',
                        $getData[3] => 'required|',
                        strtolower($getData[6]) => ['required',
                            Rule::in([1, 'makkelijk', 'gemiddeld', 'moeilijk']),],

                        strtolower($getData[5]) => ['required|',
                            Rule::in([1, 'afgerond', 'mee bezig', 'overgedragen']),]

                    ]);
                    array_push($validate_array , [$count => $validator]);

                    $data = [];

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

                    switch($getData[5]) {
                        case 'Afgerond':
                            $data['status'] = 1;
                            break;
                        case 'Mee bezig':
                            $data['status'] = 2;
                            break;
                        case 'Overgedragen':
                            $data['status'] = 3;
                            break;
                    }
                }
                $count++;
            }
            $errors = [];
            foreach($validate_array as $key => $value){
                if ($value->fails()) {
                    array_push($errors, [$key, $value]);
                }
            }

            if(isset($errors)) {
                return view('pages.producing.activity-import')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $this->save();
            }
        }
    }


    public function save(Request $request, LAPFactory $LAPFactory, CategoryRepository $categoryRepository)
    {
        $filepath = $request->file('csv_file')->getRealPath();
        $file = fopen($filepath, "r");
        $count = 0;
        $getData = fgetcsv($file, ",");

        $count = 0;
        while ($getData !== FALSE) {
            if ($count >= 5 && !$getData[0] == "" && !$getData[1] == "") {

                $category = $this->findCategory($getData[3], $categoryRepository);

                if (strtolower(substr($getData[4], 0, 7)) === 'persoon') {
                    $resourceExplode = explode('- ', $getData[4]);
                    $data['resource'] = $resourceExplode[0];
                    $data['resource_person_id'] = strtolower($resourceExplode[1]);
                } else {
                    $data['resource'] = $getData[4];
                }
                $data['category_id'] = $category['category_id'];
                $data['newcat'] = $category['newcat'];

                $data['omschrijving'] = $getData[1];
                $data['aantaluren'] = $getData[2];
                $data['aantaluren_custom'] = $getData[2] * 60;

                $data['datum'] = strval(DateTime::createFromFormat('d/m/Y', $getData[0])->format('d-m-Y'));

                $data['extrafeedback'] = null;

                $data['internetsource'] = null;
                $data['booksource'] = null;
                $data['chain_id'] = -1;

                $LAPFactory->createLAP($data);
            }
            $count++;
        }
        return view('pages.producing.activity-import')->with('successMsg', 'works');
    }

    private function findCategory($category, CategoryRepository $categoryRepository) : array {
        $persistedCategories = $categoryRepository->categoriesAvailableForStudent(Student::findOrFail(Auth::user()->getAuthIdentifier()));
        $availableCategories = [];

        foreach ($persistedCategories as $filteredCategory) { $availableCategories[$filteredCategory->category_label] = $filteredCategory->category_id; }

        if(array_key_exists($category, $availableCategories)) {
            return ['category_id' => $availableCategories[$category],
                    'newcat' => null];
        }
        else {
            return ['category_id' => 'new',
                    'newcat' => $category];
        }
    }
}

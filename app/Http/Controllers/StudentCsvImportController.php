<?php

namespace App\Http\Controllers;

use App\Repository\Eloquent\CategoryRepository;
use App\Rules\CsvDateTimeFormat;
use App\Services\Factories\LAPFactory;
use App\Student;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentCsvImportController extends Controller
{

    public function save(Request $request, LAPFactory $LAPFactory, CategoryRepository $categoryRepository)
    {
        $activities = $this->transferCsvToArray($request);
        $errors = $this->validateCsvEntries($activities);

        if(empty($errors)) {
            foreach($activities as $activity) {
                $activity = $this->prepareActivityForPersistance($activity, $categoryRepository);

                $LAPFactory->createLAP($activity);
            }
        }
        else {
            return view('pages.producing.activity-import')->with('errors', $errors);
        }

        return view('pages.producing.activity-import')->with('successMsg', 'works');
    }

    private function transferCsvToArray(Request $request) : array {
        $request->validate(["csv_file" => 'required|mimes:csv,txt']);

        ini_set("auto_detect_line_endings", true);
        $filepath = $request->file('csv_file')->getRealPath();
        $csv = fopen($filepath, "r");

        $delimiter = $this->findDelimiter(fgets($csv));
        $csvEntries = [];
        $currentLine = 0;

        while (($csvEntry = fgetcsv($csv, '1000', $delimiter)) !== FALSE) {
            if ($currentLine >= 5 && !$csvEntry[0] == "" && !$csvEntry[1] == "") {
                $csvEntry = array_slice($csvEntry,0, 7);
                $csvEntry = array_combine(['datum', 'omschrijving', 'aantaluren', 'category_id', 'resource', 'status', 'moeilijkheid'], $csvEntry);

                $csvEntries[($currentLine + 1)] = $csvEntry;
            }
            $currentLine++;
        }

        return $csvEntries;
    }

    private function validateCsvEntries(array $csvEntries) : array {
        $validations = $this->getCsvEntriesValidations();
        $errors = [];

        foreach ($csvEntries as $key => $csvEntry) {
            $validator = Validator::make($csvEntry, $validations);

            if($validator->fails()) {
                $errors[$key] = $validator->errors()->messages();
            }
        }

        return $errors;
    }

    private function prepareActivityForPersistance(array $activity, CategoryRepository $categoryRepository) : array {
        $category = $this->findCategory($activity['category_id'], $categoryRepository);

        if (strtolower(substr($activity['resource'], 0, 7)) === 'persoon') {
            $resourceExplode = explode('- ', $activity['resource']);
            $activity['resource'] = $resourceExplode[0];
            $activity['resource_person_id'] = strtolower($resourceExplode[1]);
        }

        switch($activity['moeilijkheid']) {
            case 'Makkelijk':
                $activity['moeilijkheid'] = 1;
                break;
            case 'Gemiddeld':
                $activity['moeilijkheid'] = 2;
                break;
            case 'Moeilijk':
                $activity['moeilijkheid'] = 3;
                break;
        }

        switch($activity['status']) {
            case 'Afgerond':
                $activity['status'] = 1;
                break;
            case 'Mee bezig':
                $activity['status'] = 2;
                break;
            case 'Overgedragen':
                $activity['status'] = 3;
                break;
        }

        $activity['datum'] = strval(DateTime::createFromFormat('d/m/Y', $activity['datum'])->format('d-m-Y'));

        $activity['aantaluren_custom'] = $activity['aantaluren'] * 60;
        $activity['category_id'] = $category['category_id'];
        $activity['newcat'] = $category['newcat'];

        $activity['extrafeedback'] = null;
        $activity['internetsource'] = null;
        $activity['booksource'] = null;
        $activity['chain_id'] = -1;

        return $activity;
    }

    private function getCsvEntriesValidations() : array {
        return
            [
                'datum'         => 'required', new CsvDateTimeFormat, 'after:tomorrow|date_format:d-m-Y',
                'omschrijving'  => 'required|string|max:1000',
                'category_id'   => 'required|string',
                'resource'      => 'required|string',
                'aantaluren'    => 'required|numeric|min:0|max:24',
                'moeilijkheid'  => ['required', 'string', Rule::in(['Makkelijk', 'Gemiddeld', 'Moeilijk']),],
                'status'        => ['required', 'string', Rule::in(['Afgerond', 'Mee bezig', 'Overgedragen']),]];
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

    private function findDelimiter( string $csvString) : string {
        $delimiters = array(';' => 0, ',' => 0, "\t" => 0, "|" => 0);

        foreach ($delimiters as $delimiter => &$count)
        {
            $count = count(str_getcsv($csvString, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }


}
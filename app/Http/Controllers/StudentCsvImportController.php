<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Rules\CsvDateInLearningPeriod;
use App\Rules\CsvDateTimeFormat;
use App\Rules\ResourcePersonNotNull;
use App\Services\CustomProducingEntityHandler;
use App\Services\Factories\CategoryFactory;
use App\Services\Factories\LAPFactory;
use App\Student;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentCsvImportController extends Controller
{

    public function save(Request $request,
                         LAPFactory $LAPFactory,
                         CategoryRepository $categoryRepository,
                         ResourcePersonRepository $resourcePersonRepository,
                         CustomProducingEntityHandler $customProducingEntityHandler) {
        $activities = $this->transferCsvToArray($request);
        $errors = $this->validateCsvEntries($activities);

        if(empty($errors)) {
            $student = Student::findOrFail(Auth::user()->getAuthIdentifier());
            $persistedResourcePersons = $resourcePersonRepository->resourcePersonsAvailableForStudent($student);

            foreach($activities as $activity) {
                $activity = $this->prepareActivityForPersistance($activity, $categoryRepository, $student, $persistedResourcePersons, $customProducingEntityHandler);
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

        ini_set('auto_detect_line_endings', 'true');
        $filepath = $request->file('csv_file')->getRealPath();
        $csv = fopen($filepath, "r");

        $delimiter = $this->getDelimiter(fgets($csv));
        $csvEntries = [];
        $currentLine = 1;

        while (($csvEntry = fgetcsv($csv, '1000', $delimiter)) !== FALSE) {
            if ($currentLine >= 5 && !(strlen(implode($csvEntry)) == 0)) {
                $csvEntry = array_slice($csvEntry,0, 7);

                if (strpos($csvEntry[2], ',')) {
                    $csvEntry[2] = str_replace(',', '.', $csvEntry[2]);
                }

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

    private function prepareActivityForPersistance(array $activity,
                                                   CategoryRepository $categoryRepositories,
                                                   Student $student,
                                                   array $persistedResourcePersons,
                                                   CustomProducingEntityHandler $customProducingEntityHandler
                                                   ) : array
    {
        $category = $this->getCategory($activity['category_id'], $student, $categoryRepositories);
        $resource = $this->getResource(strtolower($activity['resource']), $persistedResourcePersons);

        $activity['resource'] = $resource['resource'];
        $activity['personsource'] = $resource['personsource'];
        $activity['newswv'] = $resource['newswv'];

        $activity['moeilijkheid'] = $this->getMoeilijkheid($activity['moeilijkheid']);
        $activity['status'] = $this->getStatus($activity['status']);
        $activity['datum'] = strval(DateTime::createFromFormat('m/d/Y', $activity['datum'])->format('d-m-Y'));
        $activity['aantaluren_custom'] = $activity['aantaluren'] * 60;
        $activity['category_id'] = $category['category_id'];
        $activity['newcat'] = $category['newcat'];
        $activity['extrafeedback'] = null;
        $activity['internetsource'] = null;
        $activity['booksource'] = null;
        $activity['chain_id'] = -1;

        return $customProducingEntityHandler->process($activity);
    }

    private function getCsvEntriesValidations() : array {
        return
            [
                'datum'         => ['required', new CsvDateTimeFormat, 'before:tomorrow', new csvDateInLearningPeriod],
                'omschrijving'  => 'required|string|max:1000',
                'category_id'   => 'required|string',
                'resource'      => ['required', 'string', resolve(ResourcePersonNotNull::class)],
                'aantaluren'    => 'required|numeric|min:0|max:24',
                'moeilijkheid'  => ['required', 'string', Rule::in(['Makkelijk', 'Gemiddeld', 'Moeilijk']),],
                'status'        => ['required', 'string', Rule::in(['Afgerond', 'Mee bezig', 'Overgedragen']),]];
    }

    private function getCategory(string $category, Student $student, CategoryRepository $categoryRepository) : array {
        $persistedCategories = $categoryRepository->categoriesAvailableForStudent($student);
        $category = strtolower($category);
        $availableCategories = [];

        foreach ($persistedCategories as $filteredCategory) { $availableCategories[strtolower($filteredCategory->category_label)] = $filteredCategory->category_id; }

        if(!array_key_exists($category, $availableCategories)) {
            return ['category_id' => 'new',
                'newcat' => $category];
        }

        return ['category_id' => $availableCategories[$category],
                'newcat' => null];
    }

    private function getResource(string $resource, array $persistedResourcePersons) : array {
        $availableResourcePersons = [];

        $completeResource = [];
        $completeResource['personsource'] = null;
        $completeResource['newswv'] = null;
        $completeResource['resource'] = null;

        if (substr($resource, 0, 7) === 'persoon') {
            $resourcePerson = trim(explode('- ', $resource)[1]);

            foreach ($persistedResourcePersons as $persistedResourcePerson) {
                $availableResourcePersons[strtolower($persistedResourcePerson->person_label)] = $persistedResourcePerson->rp_id;
            }

            if(array_key_exists($resourcePerson, $availableResourcePersons)) {
                $completeResource['personsource']= $availableResourcePersons[$resourcePerson];
            }
            else if($resourcePerson != '') {
                $completeResource['personsource'] = 'new';
                $completeResource['newswv'] = $resourcePerson;
            }
            $completeResource['resource'] = 'persoon';
        }
        elseif (substr($resource, 0, 7) !== 'alleen' || substr($resource, 0, 7) !== 'persoon') {
            switch ($resource) {
                case 'boek/artikel':
                    $completeResource['resource'] = 'boek';
                    break;
                case 'internetbron':
                    $completeResource['resource'] = 'internet';
                    break;
                default:
                    $completeResource['resource'] = $resource;
                    break;
            }
        }
        return $completeResource;
    }

    private function getMoeilijkheid(string $moeilijkheid) : int {
        switch(strtolower($moeilijkheid)) {
            case 'makkelijk':
                return 1;
            case 'gemiddeld':
                return 2;
            case 'moeilijk':
                return 3;
        }
    }

    private function getStatus(string $status) : int {
        switch(strtolower($status)) {
            case 'afgerond':
                return 1;
            case 'mee bezig':
                return 2;
            case 'overgedragen':
                return 3;
        }
    }

    private function getDelimiter(string $csvString) : string {
        $delimiters = array(';' => 0, ',' => 0, "\t" => 0, "|" => 0);

        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($csvString, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\WorkplaceLearningPeriod;

class UpdateTeacherForWorkplaceLearningPeriodCSV extends Controller
{
    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

//    /**
//     * @var StudentRepository
//     */
//    private $studentRepository;

    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

    public function __construct(
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService,
        StudentSystemServiceInterface $studentSystemService
        //StudentRepository $studentRepository
    ) {
        $this->progressRegistrySystemService = $progressRegistrySystemService;
        $this->studentSystemService = $studentSystemService;
//        $this->studentRepository = $studentRepository;
    }

    public function read(Request $request)
    {

            if($request->hasFile('file'))
            {
                $filepath = $request->file('file')->getRealPath();
                $file = fopen($filepath, "r");
                $row = 1;
                $pairs =  array();
                $notKnownStudents = array();
                while (($getData = fgetcsv($file, 1000, ";")) !== FALSE)
                {
                    $studentMail = $getData[3];
                    $studentNumber = $getData[2];
                    $teacherLastname = $getData[0];
                    $teacherEmail = $getData[1];

                    //skip head of table
                    if($row == 1){ $row++; continue; }

            
//                    $student = $this->studentRepository->findByEmailOrCanvasId($studentMail, $studentMail);
                    $student = $this->studentSystemService->findByEmailOrCanvasId($studentMail, $studentMail);
           

                    if(
                        $student == null ||
//                        $this->studentRepository->findByEmailOrCanvasId($studentMail, $studentMail) != $this->studentRepository->findByStudentNumber($studentNumber) ||
//                        $this->studentRepository->findByEmailOrCanvasId($teacherEmail, $teacherEmail) != $this->studentRepository->findByLastName($teacherLastname) ||
                        $this->studentSystemService->findByEmailOrCanvasId($studentMail, $studentMail) != $this->studentSystemService->findByStudentNumber($studentNumber) ||
                        $this->studentSystemService->findByEmailOrCanvasId($teacherEmail, $teacherEmail) != $this->studentSystemService->findByLastName($teacherLastname) ||
                        !$student->hasCurrentWorkplaceLearningPeriod()
                        
                        ) {
                        $notKnownStudents[] = $studentMail;
                        continue;
                    } else {
//                        $teacher = $this->studentRepository->findByEmailOrCanvasId($teacherEmail, $teacherEmail);
                        $teacher = $this->studentSystemService->findByEmailOrCanvasId($teacherEmail, $teacherEmail);
                        $workplace = $student->getCurrentWorkplace()->wp_name;
                        $pair = new \stdClass();
                        $pair->student = $student;
                        $pair->teacher = $teacher;
                        $pair->workplace = $workplace;
                        array_push($pairs, $pair); 
                    }
            
                }

                return view('pages.admin.csv_details', [
                'pairs' => $pairs,
                'notKnownStudents' => $notKnownStudents,
                ]);
                     
            }
    }

    public function save(Request $request) {
        $tableData = stripcslashes($_POST['tableData']);
        $tableData = json_decode($tableData, TRUE);

        foreach($tableData as $row){
//            $teacher = $this->studentRepository->findByEmailOrCanvasId($row['TeacherEmail'], $row['TeacherEmail']);
//            $student = $this->studentRepository->findByEmailOrCanvasId($row['StudentEmail'], $row['StudentEmail']);
            $teacher = $this->studentSystemService->findByEmailOrCanvasId($row['TeacherEmail'], $row['TeacherEmail']);
            $student = $this->studentSystemService->findByEmailOrCanvasId($row['StudentEmail'], $row['StudentEmail']);
            $wplp = $student->getCurrentWorkplaceLearningPeriod();
            
            $this->saveWPLP($wplp, $teacher);

        }
        echo 'succes';
    }

    private function saveWPLP($wplp, $teacher) {
        $wplp->teacher_id = $teacher->student_id;
        //$this->workplaceLearningPeriodRepository->save($wplp);
        $this->progressRegistrySystemService->saveWorkplaceLearningPeriod($wplp);
    }
 

}
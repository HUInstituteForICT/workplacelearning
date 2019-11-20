<?php

declare(strict_types=1);

namespace app\Http\Controllers\Admin;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\WorkplaceLearningPeriod;

class UpdateTeacherForWorkplaceLearningPeriodCSV extends Controller
{
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    public function __construct(WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository, StudentRepository $studentRepository)
    {
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
        $this->studentRepository = $studentRepository;
    }



    public function __invoke(Request $request)
    {
            $filename=$_FILES["file"]["tmp_name"];

            if($_FILES["file"]["size"] > 0)
            {
                $file = fopen($filename, "r");
                $row = 1;
                $pairs =  array();
                $notKnownStudents = array();
                while (($getData = fgetcsv($file, 1000, ";")) !== FALSE)
                {
                    //skip head of table
                    if($row == 1){ $row++; continue; }

            
                    $student = $this->studentRepository->findByEmailOrCanvasId($getData[3], $getData[3]);
           

                    if(
                        $student == null ||
                        $this->studentRepository->findByEmailOrCanvasId($getData[3], $getData[3]) != $this->studentRepository->findByStudentNumber($getData[2]) ||
                        $this->studentRepository->findByEmailOrCanvasId($getData[1], $getData[1]) != $this->studentRepository->findByLastName($getData[0]) ||
                        !$student->hasCurrentWorkplaceLearningPeriod()
                        
                        ) {
                        array_push($notKnownStudents, $getData[3]);
                        continue;
                    } else {
                        $docent = $this->studentRepository->findByEmailOrCanvasId($getData[1], $getData[1]);
                        $workplace = $student->getCurrentWorkplace()->wp_name;
                        $pair = new \stdClass();
                        $pair->student = $student;
                        $pair->docent = $docent;
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
            $docent = $this->studentRepository->findByEmailOrCanvasId($row['TeacherEmail'], $row['TeacherEmail']);
            $student = $this->studentRepository->findByEmailOrCanvasId($row['StudentEmail'], $row['StudentEmail']);
            $wplp = $student->getCurrentWorkplaceLearningPeriod();
            
            $this->saveWPLP($wplp, $docent);

        }
        echo 'succes';
    }

    public function saveWPLP($wplp, $docent) {
        $wplp->teacher_id = $docent->student_id;
        $this->workplaceLearningPeriodRepository->save($wplp);

    }
 

}
<?php

namespace App\Repository\Eloquent;

use App\Services\EvidenceFileHandler;
use App\UserSetting;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkplaceLearningPeriodRepository
{
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    public function get(int $id): WorkplaceLearningPeriod
    {
        return WorkplaceLearningPeriod::findOrFail($id);
    }

    public function save(WorkplaceLearningPeriod $workplaceLearningPeriod): bool
    {
        return $workplaceLearningPeriod->save();
    }

    public function update(WorkplaceLearningPeriod $wplPeriod, array $data): bool
    {
        $wplPeriod->startdate = Carbon::parse($data['startdate']);
        $wplPeriod->enddate = Carbon::parse($data['enddate']);
        $wplPeriod->nrofdays = $data['numdays'];
        $wplPeriod->description = $data['internshipAssignment'];

        return $wplPeriod->save();
    }

    public function delete(WorkplaceLearningPeriod $workplaceLearningPeriod): void
    {
        DB::transaction(function () use ($workplaceLearningPeriod) {
            // Go from bottom to top in relationship chain

            foreach ($workplaceLearningPeriod->learningActivityActing as $activityActing) {
                $activityActing->competence()->detach();
                $activityActing->reflection()->delete();
                foreach ($activityActing->evidence as $evidence) {
                    $this->evidenceFileHandler->delete($evidence);
                    $evidence->delete();
                }
            }

            foreach ($workplaceLearningPeriod->learningActivityProducing as $activityProducing) {
                $activityProducing->feedback()->delete();
            }

            // Counter foreign key exceptions
            $workplaceLearningPeriod->learningActivityProducing()->update(['prev_lap_id' => null]);

            $workplaceLearningPeriod->learningActivityProducing()->delete();
            $workplaceLearningPeriod->learningActivityActing()->delete();

            $workplaceLearningPeriod->chains()->delete();
            $workplaceLearningPeriod->categories()->delete();
            $workplaceLearningPeriod->learningGoals()->delete();
            $workplaceLearningPeriod->resourcePerson()->delete();
            $workplaceLearningPeriod->timeslot()->delete();
            $workplaceLearningPeriod->resourceMaterial()->delete();

            UserSetting::where([
                ['setting_label', '=', 'active_internship'],
                ['setting_value', '=', $workplaceLearningPeriod->wplp_id],
                ['student_id', '=', $workplaceLearningPeriod->student_id],
            ])->delete();

            $workplace = $workplaceLearningPeriod->workplace;
            // Finally, delete itself
            $workplaceLearningPeriod->delete();

            $workplace->delete();
        });
    }
}

<?php

declare(strict_types=1);

use App\LearningActivityActing;
use Illuminate\Database\Migrations\Migration;

class MoveEvidenceFromLAAToEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        /** @var \Illuminate\Database\Eloquent\Collection|LearningActivityActing[] $activities */
        $activities = LearningActivityActing::whereNotNull('evidence_filename')
            ->get();

        $activities->each(function (LearningActivityActing $learningActivityActing) {
            $learningActivityActing->evidence()->create([
                'filename'      => $learningActivityActing->evidence_filename,
                'disk_filename' => $learningActivityActing->evidence_disk_filename,
                'mime'          => $learningActivityActing->evidence_mime,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}

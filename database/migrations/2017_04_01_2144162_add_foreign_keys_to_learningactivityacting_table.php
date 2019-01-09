<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLearningactivityactingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
            $table->foreign('learninggoal_id', 'fk_LearningActivityActing_LearningGoal1')->references('learninggoal_id')->on('learninggoal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_material_id', 'fk_LearningActivityActing_ResourceMaterial1')->references('rm_id')->on('resourcematerial')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_person_id', 'fk_LearningActivityActing_ResourcePerson1')->references('rp_id')->on('resourceperson')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('timeslot_id', 'fk_LearningActivityActing_Timeslot1')->references('timeslot_id')->on('timeslot')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('wplp_id', 'fk_LearningActivityActing_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGACTIVITYACTING_LEARNINGGOAL1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGACTIVITYACTING_RESOURCEMATERIAL1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGACTIVITYACTING_RESOURCEPERSON1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGACTIVITYACTING_TIMESLOT1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_LEARNINGACTIVITYACTING_WORKPLACELEARNINGPERIOD1');
            }
        });
    }
}

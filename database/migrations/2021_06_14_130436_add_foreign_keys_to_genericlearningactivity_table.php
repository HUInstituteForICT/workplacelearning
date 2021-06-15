<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGenericlearningactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genericlearningactivity', function (Blueprint $table) {
            $table->foreign('wplp_id', 'fk_genericlearningactivity_WorkplaceLearningPeriod1')->references('wplp_id')->on('workplacelearningperiod')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_person_id', 'fk_genericlearningactivity_ResourcePerson1')->references('rp_id')->on('resourceperson')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('res_material_id', 'fk_genericlearningactivity_ResourceMaterial1')->references('rm_id')->on('resourcematerial')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('category_id', 'fk_genericlearningactivity_Category1')->references('category_id')->on('category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('difficulty_id', 'fk_genericlearningactivity_Difficulty1')->references('difficulty_id')->on('difficulty')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('status_id', 'fk_genericlearningactivity_Status1')->references('status_id')->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('timeslot_id', 'fk_genericlearningactivity_Timeslot1')->references('timeslot_id')->on('timeslot')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('learninggoal_id', 'fk_genericlearningactivity_LearningGoal1')->references('learninggoal_id')->on('learninggoal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('chain_id', 'fk_genericlearningactivity_Chain1')->references('id')->on('chains')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genericlearningactivity', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_CATEGORY1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_DIFFICULTY1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_RESOURCEMATERIAL1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_RESOURCEPERSON1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_STATUS1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_WORKPLACELEARNINGPERIOD1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_LEARNINGGOAL1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_TIMESLOT1');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_GENERICLEARNINGACTIVITY_CHAINS1');
            }
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLearningactivityactingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('learningactivityacting');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('learningactivityacting', function (Blueprint $table): void {
            $table->integer('laa_id', true);
            $table->integer('wplp_id')->index('fk_LearningActivityActing_WorkplaceLearningPeriod1_idx');
            $table->date('date');
            $table->integer('timeslot_id')->index('fk_LearningActivityActing_Timeslot1_idx');
            $table->string('situation', 250);
            $table->string('lessonslearned', 250);
            $table->string('support_wp', 125)->nullable();
            $table->string('support_ed', 125)->nullable();
            $table->integer('res_person_id')->index('fk_LearningActivityActing_ResourcePerson1_idx');
            $table->integer('res_material_id')->nullable()->index('fk_LearningActivityActing_ResourceMaterial1_idx');
            $table->string('res_material_detail', 75)->nullable();
            $table->integer('learninggoal_id')->nullable()->index('fk_LearningActivityActing_LearningGoal1_idx');
            $table->integer('chain_id')->unsigned()->nullable()->index('chain_to_lap');

        });
    }
}

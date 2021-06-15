<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenericlearningactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genericlearningactivity', function (Blueprint $table) {
            $table->integer('gla_id', true);
            $table->integer('wplp_id')->index('fk_genericlearningactivity_WorkplaceLearningPeriod1_idx');
            $table->integer('res_person_id')->nullable()->index('fk_genericlearningactivity_ResourcePerson1_idx');
            $table->integer('res_material_id')->nullable()->index('fk_genericlearningactivity_ResourceMaterial1_idx');
            $table->integer('category_id')->index('fk_genericlearningactivity_Category1_idx');
            $table->integer('difficulty_id')->index('fk_genericlearningactivity_Difficulty1_idx');
            $table->integer('status_id')->index('fk_genericlearningactivity_Status1_idx');
            $table->integer('timeslot_id')->index('fk_genericlearningactivity_Timeslot1_idx');
            $table->integer('learninggoal_id')->nullable()->index('fk_genericlearningactivity_LearningGoal1_idx');
            $table->integer('chain_id')->unsigned()->nullable()->index('fk_genericlearningactivity_Chain1_idx');
            $table->string('learningactivity_name', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('genericlearningactivity');
    }
}
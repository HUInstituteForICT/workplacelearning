<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnLearningactivityIdInActivityforcompetenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activityforcompetence', function (Blueprint $table) {
            $table->dropColumn(['learningactivity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activityforcompetence', function (Blueprint $table) {
            $table->integer('learningactivity_id')->index('fk_ActivityForCompetency_LearningActivityActing1_idx');
        });
    }
}

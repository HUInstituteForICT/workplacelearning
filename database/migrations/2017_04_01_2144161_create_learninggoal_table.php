<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLearninggoalTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('learninggoal', function (Blueprint $table): void {
            $table->integer('learninggoal_id', true);
            $table->string('learninggoal_label', 45);
            $table->integer('wplp_id')->index('fk_LearningGoal_WorkplaceLearningPeriod1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('learninggoal');
    }
}

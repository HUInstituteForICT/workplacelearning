<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityforcompetenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activityforcompetence', function (Blueprint $table): void {
            $table->integer('afc_id', true);
            $table->integer('competence_id')->index('fk_ActivityForCompetency_Competency1');
            $table->integer('learningactivity_id')->index('fk_ActivityForCompetency_LearningActivityActing1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('activityforcompetence');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competence', function (Blueprint $table): void {
            $table->integer('competence_id', true);
            $table->string('competence_label', 45);
            $table->integer('educationprogram_id')->index('fk_Competency_EducationProgram1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('competence');
    }
}

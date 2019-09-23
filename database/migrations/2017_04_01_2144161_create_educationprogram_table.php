<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEducationprogramTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('educationprogram', function (Blueprint $table): void {
            $table->integer('ep_id', true);
            $table->string('ep_name', 45);
            $table->integer('eptype_id')->index('fk_EducationProgram_EducationProgramType1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop('educationprogram');
        Schema::enableForeignKeyConstraints();
    }
}

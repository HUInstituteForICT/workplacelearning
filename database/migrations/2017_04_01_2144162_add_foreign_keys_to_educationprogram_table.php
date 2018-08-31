<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEducationprogramTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('educationprogram', function (Blueprint $table): void {
            $table->foreign('eptype_id', 'fk_EducationProgram_EducationProgramType1')->references('eptype_id')->on('educationprogramtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educationprogram', function (Blueprint $table): void {
            $table->dropForeign('fk_EducationProgram_EducationProgramType1');
        });
    }
}

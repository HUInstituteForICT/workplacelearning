<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompetenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('competence', function (Blueprint $table): void {
            $table->foreign('educationprogram_id', 'fk_Competency_EducationProgram1')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competence', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('FK_COMPETENCY_EDUCATIONPROGRAM1');
            }
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeysCompetencedescriptionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('competence_descriptions', function (Blueprint $table) {
            $table->foreign('education_program_id', 'fk_CompetenceDescription_EducationProgram')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('competence_descriptions', function (Blueprint $table) {
            $table->dropForeign('fk_CompetenceDescription_EducationProgram');
        });
    }
}

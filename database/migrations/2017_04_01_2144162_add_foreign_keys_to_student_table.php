<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStudentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->foreign('ep_id', 'fk_Student_EducationProgram')->references('ep_id')->on('educationprogram')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropForeign('fk_Student_EducationProgram');
        });
    }
}

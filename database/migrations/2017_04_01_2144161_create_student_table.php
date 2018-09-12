<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student', function (Blueprint $table): void {
            $table->integer('student_id', true);
            $table->integer('studentnr')->unique('studentnr_UNIQUE');
            $table->string('firstname', 45);
            $table->string('lastname', 45);
            $table->integer('ep_id')->index('fk_Student_EducationProgram_idx');
            $table->integer('userlevel');
            $table->string('pw_hash', 128);
            $table->string('gender', 1);
            $table->date('birthdate')->nullable();
            $table->string('email')->unique('email_UNIQUE');
            $table->string('phonenr', 45)->nullable();
            $table->dateTime('registrationdate')->nullable();
            $table->string('answer', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('student');
    }
}

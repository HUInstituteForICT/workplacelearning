<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflectionMethodInterviewParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reflection_method_interview_participations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->nullable(false);
            $table->boolean('participates')->nullable(false)->default(false);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        Schema::table('reflection_method_interview_participations', function(Blueprint $table) {
            $table->foreign('student_id', 'optin_to_student_id')
                ->references('student_id')->on('student')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reflection_method_interview_participations');
    }
}

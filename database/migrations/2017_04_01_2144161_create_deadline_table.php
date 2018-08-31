<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeadlineTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('deadline', function (Blueprint $table) {
            $table->integer('dl_id', true);
            $table->integer('student_id')->index('fk_Deadline_Student1_idx');
            $table->string('dl_value', 45)->nullable();
            $table->dateTime('dl_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('deadline');
    }
}

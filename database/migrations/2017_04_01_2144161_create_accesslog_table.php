<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccesslogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('accesslog', function (Blueprint $table) {
            $table->integer('access_id', true);
            $table->integer('student_id')->index('fk_AccessLog_Student1_idx');
            $table->string('session_id', 64);
            $table->string('user_ip', 45)->nullable();
            $table->integer('screen_width')->nullable();
            $table->integer('screen_height')->nullable();
            $table->string('user_agent', 256)->nullable();
            $table->string('OS', 256)->nullable();
            $table->string('url', 2000);
            $table->dateTime('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('accesslog');
    }
}

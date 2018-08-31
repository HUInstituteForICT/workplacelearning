<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDifficultyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('difficulty', function (Blueprint $table) {
            $table->integer('difficulty_id', true);
            $table->string('difficulty_label', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('difficulty');
    }
}

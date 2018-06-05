<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name');
            $table->smallInteger('operator')->nullable();
            $table->string('education_program_type');
            $table->string('select_type')->nullable();
            $table->integer('statistic_variable_one_id')->nullable();
            $table->integer('statistic_variable_two_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statistics');
    }
}

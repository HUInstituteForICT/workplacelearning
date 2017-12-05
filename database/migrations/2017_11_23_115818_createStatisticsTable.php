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
            $table->smallInteger('operator');
            $table->string('name');
            $table->smallInteger('education_program_type_id');
            $table->integer('statistic_variable_one_id');
            $table->integer('statistic_variable_two_id');
            $table->integer('tip_id')->nullable();
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_variables', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('dataUnitMethod');
            $table->string('dataUnitParameterValue');
            $table->integer('statistic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statistic_variables');
    }
}

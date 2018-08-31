<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChartTable extends Migration
{
    public function up()
    {
        Schema::create('chart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('analysis_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('label', 255);
        });
    }

    public function down()
    {
        Schema::drop('chart');
    }
}

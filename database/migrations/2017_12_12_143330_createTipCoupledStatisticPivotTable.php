<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipCoupledStatisticPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tip_coupled_statistic', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('tip_id')->unsigned();
            $table->integer('statistic_id')->unsigned();
            $table->smallInteger('comparison_operator')->unsigned();
            $table->float('threshold');
            $table->boolean("multiplyBy100");


            $table->unique(['tip_id', 'statistic_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tip_coupled_statistic');
    }
}

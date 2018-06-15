<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortTipPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohort_tip', function (Blueprint $table) {
            $table->integer('cohort_id')->unsigned();
            $table->integer('tip_id')->unsigned();

            $table->foreign('cohort_id')->references('id')->on('cohorts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tip_id')->references('id')->on('tips')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['cohort_id', 'tip_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cohort_tip');
    }
}

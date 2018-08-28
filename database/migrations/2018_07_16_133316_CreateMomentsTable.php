<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMomentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moments', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('rangeStart');
           $table->integer('rangeEnd');
           $table->unsignedInteger('tip_id');
        });

        Schema::table('moments', function(Blueprint $table) {
            $table->foreign('tip_id', 'moment_to_tip')->references('id')->on('tips')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moments');
    }
}

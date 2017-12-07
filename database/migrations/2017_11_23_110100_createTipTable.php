<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipTable extends Migration
{
    public function up()
    {
        Schema::create('tips', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('threshold');
            $table->text("tipText");
            $table->boolean("multiplyBy100");
            $table->boolean("showInAnalysis");

            $table->integer('statistic_id')->unsigned();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tips');
    }
}

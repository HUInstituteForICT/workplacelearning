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
            $table->string("tipText", 1000)->default('');
            $table->boolean("showInAnalysis")->default(true);
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

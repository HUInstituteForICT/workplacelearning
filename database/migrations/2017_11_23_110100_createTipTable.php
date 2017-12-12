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
            $table->text("tipText");
            $table->boolean("showInAnalysis");
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

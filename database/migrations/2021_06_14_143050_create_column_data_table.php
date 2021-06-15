<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column_data', function (Blueprint $table) {
            $table->integer('column_data_id', true);
            $table->integer('column_id')->index('fk_columnId_Column1_idx');
            $table->string('data_type', 250);
            $table->string('data_as_string', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('column_data');
    }
}
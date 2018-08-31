<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEducationprogramtypeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('educationprogramtype', function (Blueprint $table) {
            $table->integer('eptype_id', true);
            $table->string('eptype_name', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('educationprogramtype');
    }
}

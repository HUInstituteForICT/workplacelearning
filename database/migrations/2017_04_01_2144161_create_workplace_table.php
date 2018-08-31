<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkplaceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('workplace', function (Blueprint $table) {
            $table->integer('wp_id', true);
            $table->string('wp_name', 100);
            $table->string('street', 45);
            $table->string('housenr', 45);
            $table->string('postalcode', 45);
            $table->string('town', 45);
            $table->string('contact_name', 100);
            $table->string('contact_email');
            $table->string('contact_phone', 20);
            $table->integer('numberofemployees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('workplace');
    }
}

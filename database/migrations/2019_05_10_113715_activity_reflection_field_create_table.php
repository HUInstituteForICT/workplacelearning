<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivityReflectionFieldCreateTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_reflection_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_reflection_id')->unsigned();
            $table->string('name');
            $table->text('value');
        });

        Schema::table('activity_reflection_fields', function (Blueprint $table) {
            $table->foreign('activity_reflection_id', 'reflection_field_to_reflection')
                ->references('id')
                ->on('activity_reflections')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('activity_reflection_fields');
    }
}

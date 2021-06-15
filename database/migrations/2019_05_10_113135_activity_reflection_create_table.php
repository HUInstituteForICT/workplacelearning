<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityReflectionCreateTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_reflections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('learning_activity_id');
            $table->string('learning_activity_type');
            $table->string('reflection_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('activity_reflections');
    }
}

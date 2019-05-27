<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameActivityReflections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('activity_reflection', 'activity_reflections');
        Schema::rename('activity_reflection_field', 'activity_reflection_fields');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('activity_reflections', 'activity_reflection');
        Schema::rename('activity_reflection_fields', 'activity_reflection_field');
    }
}

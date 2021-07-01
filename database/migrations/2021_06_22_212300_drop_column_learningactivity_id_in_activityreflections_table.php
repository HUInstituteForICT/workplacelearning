<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnLearningactivityIdInActivityreflectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_reflections', function (Blueprint $table) {
            $table->dropColumn(['learning_activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_reflections', function (Blueprint $table) {
            $table->integer('learning_activity_id');
        });
    }
}

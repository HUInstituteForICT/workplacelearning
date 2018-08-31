<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToLearningGoal extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learninggoal', function (Blueprint $table) {
            $table->string('description', 255)->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learninggoal', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}

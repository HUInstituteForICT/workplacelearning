<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeLessonsLearnedNullableOnLAA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('lessonslearned', 1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->string('lessonslearned', 1000)->nullable(false)->change();
        });
    }
}

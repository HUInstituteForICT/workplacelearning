<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LAAAddReflectionFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learningactivityacting', function (Blueprint $table) {
            $table->boolean('is_from_reflection_beta')->default(false);
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
            $table->removeColumn('is_from_reflection_beta');
        });
    }
}

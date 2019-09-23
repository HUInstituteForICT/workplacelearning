<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIsBetaPropertyFromLaa extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('learningactivityacting', static function (Blueprint $table) {
            $table->dropColumn(['is_from_reflection_beta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('learningactivityacting', static function (Blueprint $table) {
            $table->boolean('is_from_reflection_beta')->default(false);
        });
    }
}

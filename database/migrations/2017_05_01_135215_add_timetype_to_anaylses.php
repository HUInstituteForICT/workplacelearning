<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimetypeToAnaylses extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->string('type_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->dropColumn('type_time');
        });
    }
}

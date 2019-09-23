<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassNameToStatistics extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('statistics', function (Blueprint $table) {
            $table->string('className')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('statistics', function (Blueprint $table) {
        });
    }
}

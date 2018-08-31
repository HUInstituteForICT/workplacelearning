<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryToWorkplace extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('workplace', function (Blueprint $table) {
            $table->string('country')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('workplace', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
}

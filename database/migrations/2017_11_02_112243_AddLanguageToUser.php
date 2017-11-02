<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student', function(Blueprint $table) {
            $table->string('locale', 10)->default('nl');
        });

        \DB::raw("UPDATE student SET locale = 'nl' WHERE locale IS NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student', function(Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
}

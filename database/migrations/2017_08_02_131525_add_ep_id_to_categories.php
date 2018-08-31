<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEpIdToCategories extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->integer('ep_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('ep_id');
        });
    }
}

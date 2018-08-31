<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentAddAnalyticsFlag extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table) {
            $table->boolean('is_in_analytics')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table) {
            $table->dropColumn('is_in_analytics');
        });
    }
}

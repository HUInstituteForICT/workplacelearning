<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoursPerDayToWplp extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table) {
            $table->decimal('hours_per_day', 8, 1)->default(7.5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table) {
            $table->dropColumn('hours_per_day');
        });
    }
}

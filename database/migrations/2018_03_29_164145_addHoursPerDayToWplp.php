<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoursPerDayToWplp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workplacelearningperiod', function(Blueprint $table) {
            $table->decimal('hours_per_day', 8, 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workplacelearningperiod', function(Blueprint $table) {
            $table->dropColumn('hours_per_day');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToTip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tips', function(Blueprint $table) {
            $table->string('trigger')->default('statistic');
            $table->float('rangeStart')->nullable();
            $table->float('rangeEnd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tips', function(Blueprint $table) {
            $table->dropColumn(['trigger', 'rangeStart', 'rangeEnd']);
        });
    }
}

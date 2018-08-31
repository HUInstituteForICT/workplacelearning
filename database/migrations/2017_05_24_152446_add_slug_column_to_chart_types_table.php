<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugColumnToChartTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chart_types', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('chart_types', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}

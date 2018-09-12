<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkToDashcharts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dashboard_charts', function (Blueprint $table): void {
            $table->foreign('chart_id')
                ->references('id')->on('chart')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dashboard_charts', function (Blueprint $table): void {
            $table->dropForeign('dashboard_charts_chart_id_foreign');
        });
    }
}

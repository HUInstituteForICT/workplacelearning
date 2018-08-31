<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration
{
    public function up(): void
    {
        Schema::table('labels', function (Blueprint $table): void {
            $table->foreign('chart_id')->references('id')->on('chart')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('chart', function (Blueprint $table): void {
            $table->foreign('analysis_id')->references('id')->on('analyses')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('chart', function (Blueprint $table): void {
            $table->foreign('type_id')->references('id')->on('chart_types')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('labels', function (Blueprint $table): void {
            $table->dropForeign('labels_chart_id_foreign');
        });
        Schema::table('chart', function (Blueprint $table): void {
            $table->dropForeign('chart_analysis_id_foreign');
        });
        Schema::table('chart', function (Blueprint $table): void {
            $table->dropForeign('chart_type_id_foreign');
        });
    }
}

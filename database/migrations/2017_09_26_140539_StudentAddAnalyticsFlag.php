<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentAddAnalyticsFlag extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table): void {
            $table->boolean('is_in_analytics')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workplacelearningperiod', function (Blueprint $table): void {
            $table->dropColumn('is_in_analytics');
        });
    }
}

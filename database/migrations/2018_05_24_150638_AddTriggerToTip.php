<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToTip extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tips', function (Blueprint $table): void {
            $table->string('trigger')->default('statistic');
            $table->float('rangeStart')->nullable();
            $table->float('rangeEnd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tips', function (Blueprint $table): void {
            $table->dropColumn(['trigger', 'rangeStart', 'rangeEnd']);
        });
    }
}

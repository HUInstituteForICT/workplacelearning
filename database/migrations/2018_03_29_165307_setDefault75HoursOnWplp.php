<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class SetDefault75HoursOnWplp extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('update workplacelearningperiod set hours_per_day = 7.5;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
}

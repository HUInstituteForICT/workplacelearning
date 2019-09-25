<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryToWorkplace extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workplace', function (Blueprint $table): void {
            $table->string('country')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workplace', function (Blueprint $table): void {
            $table->dropColumn('country');
        });
    }
}

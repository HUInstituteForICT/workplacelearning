<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EducationProgramEditDisableable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('educationprogram', function (Blueprint $table): void {
            $table->boolean('disabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educationprogram', function (Blueprint $table): void {
            $table->dropColumn('disabled');
        });
    }
}

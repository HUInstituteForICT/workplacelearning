<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageToUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student', function (Blueprint $table): void {
            $table->string('locale', 10)->default('nl');
        });

        \DB::raw("UPDATE student SET locale = 'nl' WHERE locale IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table): void {
            $table->dropColumn('locale');
        });
    }
}

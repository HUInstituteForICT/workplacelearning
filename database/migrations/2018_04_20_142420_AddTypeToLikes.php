<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToLikes extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table): void {
            $table->integer('type')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table): void {
            $table->dropColumn('type');
        });
    }
}

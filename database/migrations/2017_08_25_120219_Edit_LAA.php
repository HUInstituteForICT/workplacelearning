<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLAA extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
            $table->string('situation', 1000)->change();
            $table->string('lessonslearned', 1000)->change();
            $table->string('support_wp', 500)->change();
            $table->string('support_ed', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learningactivityacting', function (Blueprint $table): void {
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToLearningGoal extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learninggoal', function (Blueprint $table): void {
            $table->string('description', 255)->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learninggoal', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
}

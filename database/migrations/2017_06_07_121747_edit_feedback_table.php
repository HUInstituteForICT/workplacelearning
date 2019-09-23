<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->string('notfinished', 100)->nullable()->default(null)->change();
            $table->integer('progress_satisfied')->default(0)->change();
            $table->integer('support_requested')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->string('notfinished', 100)->change();
            $table->integer('progress_satisfied')->change();
            $table->integer('support_requested')->change();
        });
    }
}
